<?php

namespace App\Services;

use App\Models\Hoa;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class HoaService implements HoaServiceInterface
{
  public function getviewHoa()
  {
    $user = Auth::user();
    $hoa = Hoa::where('user_id', $user->id)->first();

    if ($hoa) {
      return view('HOA.hoa-details');
    }

    return view('HOA.forms-HOA');
  }

  public function getHOA()
  {
    $user = Auth::user();
    $hoa = Hoa::where('user_id', $user->id)->first();

    if (!$hoa) {
      throw new \Exception('User has not created any HOA yet.');
    }

    return $hoa;
  }

  public function createHoa(array $data)
  {
    $user = Auth::user();

    // Check if user already created an HOA
    $existingHoa = Hoa::where('user_id', $user->id)->first();
    if ($existingHoa) {
      throw new \Exception('You have already created an HOA');
    }

    $validator = Validator::make($data, [
      'name' => 'required|string|min:3|max:255|unique:hoas,name',
      'description' => 'required|string|min:3|max:255',
      'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
      'total' => 'required|numeric|min:0',
      'address' => 'required|string|min:3|max:255',
      'price_per_month' => 'required|numeric|min:0'
    ]);

    if ($validator->fails()) {
      throw new ValidationException($validator);
    }

    $imageUrl = null;
    if (isset($data['image'])) {
      $image = $data['image'];
      if ($image->isValid()) {
        $imageName = time() . '.' . $image->getClientOriginalExtension();
        $image->move(public_path('uploads'), $imageName);
        $imageUrl = url('uploads/' . $imageName);
      } else {
        throw new \Exception("An error occurred while uploading the image.");
      }
    }

    return Hoa::create([
      'name' => $data['name'],
      'description' => $data['description'],
      'price_per_month' => $data['price_per_month'],
      'user_id' => $user->id,
      'total' => $data['total'],
      'address' => $data['address'],
      'image' => $imageUrl
    ]);
  }

  public function updateHoa(array $data)
  {
    $user = Auth::user();
    $hoa = Hoa::where('user_id', $user->id)->first();

    $validator = Validator::make($data, [
      'name' => 'required|string|min:3|max:255',
      'description' => 'required|string|min:3|max:255',
      'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
      'total' => 'required|numeric|min:0',
      'address' => 'required|string|min:3|max:255',
      'price_per_month' => 'required|numeric|min:0'
    ]);

    if ($validator->fails()) {
      throw new ValidationException($validator);
    }

    if (isset($data['image'])) {
      $image = $data['image'];
      if ($image->isValid()) {
        $imageName = time() . '.' . $image->getClientOriginalExtension();
        $image->move(public_path('uploads'), $imageName);
        $data['image'] = url('uploads/' . $imageName);
      }
    }

    $hoa->update($data);

    return $hoa;
  }


  public function deleteHoa(int $id)
  {
    $user = Auth::user();
    $hoa = Hoa::where('user_id', $user->id)->findOrFail($id);

    $hoa->delete();
  }
}
