<?php


namespace App\Traits;

use App\Models\Car;
use App\Models\Discount;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Database\Eloquent\ModelNotFoundException;

trait DiscountTrait
{
    /**
     * Create discount scenarios based on your requirements.
     * 
     * @param array $data
     *    Expected keys:
     *      - type: 'car', 'category', 'global'
     *      - car_ids: array of car IDs (only for type 'car')
     *      - category: string (only for type 'category')
     *      - code: nullable string (required for some cases)
     *      - amount: decimal (required)
     *      - start_date: date (required)
     *      - end_date: date (required)
     *      - is_active: boolean (optional, defaults to true)
     *      - description: string (optional)
     * 
     * @return Discount
     * @throws \Exception Validation or logic error
     */
    public function createDiscount(array $data)
    {
        // Common base validation rules
        $rules = [
            'type' => ['required', Rule::in(['car', 'category', 'global'])],
            'amount' => ['required', 'integer', 'min:1','max:99'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'is_active' => ['sometimes', 'boolean'],
            'description' => ['nullable', 'string', 'max:1000'], // ✅ Added validation
        ];

        // Add rules based on type
        switch ($data['type']) {
            case 'car':
                $rules['car_ids'] = ['required', 'array', 'min:1'];
                $rules['car_ids.*'] = ['integer', 'exists:cars,id'];

                $rules['code'] = [
                    'nullable',
                    'string',
                    'max:50',
                    Rule::unique('discounts', 'code')->where(function ($query) use ($data) {
                        return $query->where('type', 'car');
                    }),
                ];

                $data['category'] = null;
                break;

            case 'category':
                $rules['category'] = ['required', 'string', 'max:255'];

                $rules['code'] = [
                    'nullable',
                    'string',
                    'max:50',
                    Rule::unique('discounts', 'code')->where(function ($query) use ($data) {
                        return $query->where('type', 'category');
                    }),
                ];

                if (isset($data['car_ids'])) {
                    unset($data['car_ids']);
                }
                break;

            case 'global':
                $rules['code'] = [
                    'required',
                    'string',
                    'max:50',
                    Rule::unique('discounts', 'code')->where('type', 'global'),
                ];

                $data['category'] = null;
                if (isset($data['car_ids'])) {
                    unset($data['car_ids']);
                }
                break;
        }

        // Validate the data
        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            throw new \Exception("Discount validation failed: " . implode(", ", $validator->errors()->all()));
        }

        // Set default for is_active if not provided
        if (!isset($data['is_active'])) {
            $data['is_active'] = true;
        }

        // Create discount with description
        $discount = Discount::create([
            'type' => $data['type'],
            'category' => $data['category'] ?? null,
            'code' => $data['code'] ?? null,
            'amount' => $data['amount'],
            'start_date' => $data['start_date'],
            'end_date' => $data['end_date'],
            'is_active' => $data['is_active'],
            'description' => $data['description'] ?? null, // ✅ Added in creation
        ]);

        // Attach to cars if needed
        if ($data['type'] === 'car') {
            $carIds = $data['car_ids'];
            $cars = Car::whereIn('id', $carIds)->get();

            if ($cars->count() !== count($carIds)) {
                throw new ModelNotFoundException("One or more car IDs do not exist.");
            }

            $discount->cars()->syncWithoutDetaching($carIds);
        }

        return $discount;
    }
}
