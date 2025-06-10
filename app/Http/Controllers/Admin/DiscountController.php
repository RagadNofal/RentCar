<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Discount;
use App\Models\Car;
use App\Traits\DiscountTrait;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Models\User;
use App\Notifications\NewDiscountNotification;

class DiscountController extends Controller
{
    use DiscountTrait;

    // Show all discounts
    public function index()
    {
        $discounts = Discount::all();
        return view('admin.discounts.index', compact('discounts'));
    }

    // Show form to create a discount
    public function create()
    {
        return view('admin.discounts.create');
    }

    // Store a new discount using the trait method
    public function store(Request $request)
    {
        try {
            // Add description to data passed to createDiscount method if needed
            $data = $request->all();

           
          

            $discount = $this->createDiscount($data);

            foreach (User::where('role', 'client')->get() as $user) {
                $user->notify(new NewDiscountNotification($discount));
            }
            return redirect()->route('admin.discounts.index')
                ->with('success', 'Discount created successfully');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors($e->getMessage())
                ->withInput();
        }
    }

    // Show a single discount
    public function show(Discount $discount)
    {
        return view('admin.discounts.show', compact('discount'));
    }

    // Show form to edit a discount
    public function edit(Discount $discount)
    {
        return view('admin.discounts.edit', compact('discount'));
    }

    // Update a discount
    public function update(Request $request, $id)
    {
        try {
            $discount = Discount::findOrFail($id);

            $data = $request->all();

            // Base validation rules
            $rules = [
                'type' => ['required', Rule::in(['car', 'category', 'global'])],
                'amount' => ['required', 'numeric', 'min:0','max:99'],
                'start_date' => ['required', 'date'],
                'end_date' => ['required', 'date', 'after_or_equal:start_date'],
                'is_active' => ['sometimes', 'boolean'],
                'description' => ['nullable', 'string', 'max:1000'], // <-- Added description validation
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
                        Rule::unique('discounts', 'code')
                            ->where('type', 'car')
                            ->ignore($discount->id),
                    ];
                    $data['category'] = null;
                    break;

                case 'category':
                    $rules['category'] = ['required', 'string', 'max:255'];
                    $rules['code'] = [
                        'nullable',
                        'string',
                        'max:50',
                        Rule::unique('discounts', 'code')
                            ->where('type', 'category')
                            ->ignore($discount->id),
                    ];
                    unset($data['car_ids']);
                    break;

                case 'global':
                    $rules['code'] = [
                        'required',
                        'string',
                        'max:50',
                        Rule::unique('discounts', 'code')
                            ->where('type', 'global')
                            ->ignore($discount->id),
                    ];
                    $data['category'] = null;
                    unset($data['car_ids']);
                    break;
            }

            $validator = Validator::make($data, $rules);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Default is_active to true if not provided
            if (!isset($data['is_active'])) {
                $data['is_active'] = true;
            }

            // Update discount with description included
            $discount->update([
                'type' => $data['type'],
                'category' => $data['category'] ?? null,
                'code' => $data['code'] ?? null,
                'amount' => $data['amount'],
                'start_date' => $data['start_date'],
                'end_date' => $data['end_date'],
                'is_active' => $data['is_active'],
                'description' => $data['description'] ?? null, // <-- Added description update
            ]);

            // Sync cars if type is 'car'
            if ($data['type'] === 'car') {
                $carIds = $data['car_ids'];
                $carsCount = Car::whereIn('id', $carIds)->count();

                if ($carsCount !== count($carIds)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'One or more car IDs do not exist.'
                    ], 404);
                }

                $discount->cars()->sync($carIds);
            } else {
                // Detach if discount type changed from car to others
                if ($discount->cars()->count() > 0) {
                    $discount->cars()->detach();
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Discount updated successfully',
                'data' => $discount->load('cars'),
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Discount not found',
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update discount: ' . $e->getMessage(),
            ], 500);
        }
    }

    // Delete a discount
    public function destroy(Discount $discount)
    {
        try {
            $discount->delete();
            return redirect()->route('admin.discounts.index')
                ->with('success', 'Discount deleted successfully');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors($e->getMessage());
        }
    }

    // Toggle discount active status
    public function toggle(Discount $discount)
    {
        $discount->is_active = !$discount->is_active;
        $discount->save();

        return back()->with('success', 'Discount status updated.');
    }
}
