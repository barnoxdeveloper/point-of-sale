<?php

namespace App\Http\Controllers\API;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;

class ProductController extends Controller
{
    public function all(Request $request)
    {
        $id = $request->input('id');
        $slug = $request->input('slug');
        $name = $request->input('name');
        // $limit = $request->input('limit', 6);
        
        if ($id) {
            $product = Product::where('status', 'ACTIVE')->find($id);
            if ($product) {
                return ResponseFormatter::success(
                    $product,
                    'Data Product Berhasil diambil'
                );
            } else {
                return ResponseFormatter::error(
                    null,
                    'Data Product tidak ada',
                    404
                );
            }
        }

        if ($slug) {
            $product = Product::where('slug', $slug)->where('status', 'ACTIVE')->first();
            if ($product) {
                return ResponseFormatter::success(
                    $product,
                    'Data Product Berhasil diambil'
                );
            } else {
                return ResponseFormatter::error(
                    null,
                    'Data Product tidak ada',
                    404
                );
            }
        }

        if ($name) {
            $product = Product::query();
            $product->where('name','like','%' . $name . '%')->where('status', 'ACTIVE');
            return ResponseFormatter::success(
                // $product->paginate($limit),
                $product->get(),
                'Data Product Berhasil diambil'
            );
        } else {
            return ResponseFormatter::error(
                null,
                'Data Product tidak ada',
                404
            );
        }

    }
}
