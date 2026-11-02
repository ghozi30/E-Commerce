<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Session;

class CartItem
{
    public string $rowId;

    public $id;

    public string $name;

    public int $qty;

    public float $price;

    public array $options;

    public ?Product $model = null;

    public function __construct($id, $name, $qty, $price, array $options = [])
    {
        $this->rowId = md5($id.serialize($options));
        $this->id = $id;
        $this->name = (string) $name;
        $this->qty = max(1, (int) $qty);
        $this->price = (float) $price;
        $this->options = $options;
        $this->associate(Product::class);
    }

    public function subtotal(): float
    {
        return $this->price * $this->qty;
    }

    public function associate(string $modelClass): self
    {
        if (class_exists($modelClass)) {
            $this->model = $modelClass::find($this->id);
        }

        return $this;
    }
}

class CartService
{
    protected string $instance = 'cart';

    public function instance(string $name = 'cart'): self
    {
        $this->instance = $name;

        return $this;
    }

    public function content(): Collection
    {
        $items = Session::get('cart_'.$this->instance, []);

        // Refresh model association in case of session deserialization
        foreach ($items as $item) {
            if ($item instanceof CartItem && ! $item->model) {
                $item->associate(Product::class);
            }
        }

        return collect($items);
    }

    public function count(): int
    {
        return $this->content()->sum('qty');
    }

    public function get(string $rowId): ?CartItem
    {
        $items = Session::get('cart_'.$this->instance, []);

        $item = $items[$rowId] ?? null;
        if ($item instanceof CartItem && ! $item->model) {
            $item->associate(Product::class);
        }

        return $item;
    }

    public function add($id, $name, $qty, $price, array $options = []): CartItem
    {
        $items = Session::get('cart_'.$this->instance, []);
        $rowId = md5($id.serialize($options));

        if (isset($items[$rowId])) {
            $items[$rowId]->qty += max(1, (int) $qty);
        } else {
            $item = new CartItem($id, $name, $qty, $price, $options);
            $items[$rowId] = $item;
        }

        Session::put('cart_'.$this->instance, $items);

        return $items[$rowId];
    }

    public function update(string $rowId, int $qty): void
    {
        $items = Session::get('cart_'.$this->instance, []);

        if (isset($items[$rowId])) {
            if ($qty <= 0) {
                unset($items[$rowId]);
            } else {
                $items[$rowId]->qty = $qty;
            }
            Session::put('cart_'.$this->instance, $items);
        }
    }

    public function remove(string $rowId): void
    {
        $items = Session::get('cart_'.$this->instance, []);
        unset($items[$rowId]);
        Session::put('cart_'.$this->instance, $items);
    }

    public function destroy(): void
    {
        Session::forget('cart_'.$this->instance);
    }

    public function subtotal(): float
    {
        return (float) $this->content()->reduce(function ($carry, CartItem $item) {
            return $carry + ($item->price * $item->qty);
        }, 0.0);
    }

    public function tax(): float
    {
        return round($this->subtotal() * 0.1, 2);
    }

    public function total(): float
    {
        return $this->subtotal() + $this->tax();
    }
}
