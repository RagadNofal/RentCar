<?php

namespace App\Traits;

use App\Models\Discount;
use Carbon\Carbon;

trait HasDiscounts
{
  public function calculateDiscountedPrice($basePrice = null, $discount = null)
{
    $price = $basePrice ?? $this->price_per_day;

    if (!$discount) {
        $discount = $this->getApplicableDiscount(Carbon::today());
    }

    if ($discount) {
        $discountAmount = ($discount->amount / 100) * $price;
        return max(0, $price - $discountAmount); // Ensure it's not negative
    }

    return $price;
}


    public function getApplicableDiscount(Carbon $date)
    {
        // 1. Check car-specific discounts (with or without code)
        $carDiscount = $this->discounts()
            ->where('is_active', true)
            ->where('start_date', '<=', $date)
            ->where('end_date', '>=', $date)
            ->orderByDesc('amount') // prefer highest discount
            ->first();

        if ($carDiscount) return $carDiscount;

        // 2. Check category discount
        $categoryDiscount = Discount::where('type', 'category')
            ->where('category', $this->category)
            ->whereNull('code')
            ->where('is_active', true)
            ->where('start_date', '<=', $date)
            ->where('end_date', '>=', $date)
            ->orderByDesc('amount')
            ->first();

        if ($categoryDiscount) return $categoryDiscount;

        // 3. Check global discount (should have code and be used via checkout)
        return null;
    }

    public function getDiscountByCode($code)
    {
        $date = Carbon::today();

        // Priority: car → category → global
        // 1. Car + Code
        $carDiscount = $this->discounts()
            ->where('code', $code)
            ->where('is_active', true)
            ->where('start_date', '<=', $date)
            ->where('end_date', '>=', $date)
            ->first();

        if ($carDiscount) return $carDiscount;

        // 2. Category + Code
        $categoryDiscount = Discount::where('type', 'category')
            ->where('category', $this->category)
            ->where('code', $code)
            ->where('is_active', true)
            ->where('start_date', '<=', $date)
            ->where('end_date', '>=', $date)
            ->first();

        if ($categoryDiscount) return $categoryDiscount;

        // 3. Global Discount
        $globalDiscount = Discount::where('type', 'global')
            ->where('code', $code)
            ->where('is_active', true)
            ->where('start_date', '<=', $date)
            ->where('end_date', '>=', $date)
            ->first();

        return $globalDiscount;
    }
}
