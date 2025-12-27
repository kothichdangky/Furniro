<?php
namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ImagesProduct;
use App\Models\ProductsSize;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function product()
    {
    // Lấy kèm quan hệ extraImages và sizes
    $products = Product::with(['extraImages', 'sizes'])->paginate(8); // hoặc 4 tuỳ page

    $data = $products->map(function ($product) {
        return [
            'id' => $product->id,
            'product' => $product->product,
            'intro' => $product->intro,
            'price' => $product->price,
            'price_sale' => $product->price_sale,
            'quantity' => $product->quantity,
            'badge' => $product->badge,
            'image' => $product->image,

            // ✅ Trả thêm ảnh phụ
            'addimage' => $product->extraImages->pluck('image')->toArray(),

            // ✅ Trả về size & quantity như trong product_detail
            'sizes' => $product->sizes->pluck('size')->toArray(),
            'size_quantities' => $product->sizes->pluck('quantity')->toArray(),
        ];
    });

    return response()->json([
        'data' => $data,
        'current_page' => $products->currentPage(),
        'last_page' => $products->lastPage(),
    ]);
    }


    //search
    public function search(Request $request)
    {
         $keyword = $request->input('q');

    $products = Product::when($keyword, function ($query) use ($keyword) {
        return $query->where('product', 'LIKE', "%{$keyword}%");
    })->get();

    return response()->json($products);
    }



    function product_detail(Request $request,$id)
    {
        $products = Product::with('extraImages')->findOrFail($id);
        // Trả ra ảnh phụ là mảng đường dẫn
        $productData = $products->toArray();
        $productData['addimage'] = array_map(fn($img) => $img['image'], $products->extraImages->toArray());
        $productData['sizes'] = array_map(fn($s) => $s['size'], $products->sizes->toArray());
        // thêm để hiện quantity
        $productData['size_quantities'] = array_map(fn($s) => $s['quantity'], $products->sizes->toArray());


        $productData['all_sizes'] = ['m', 'l', 'xl'];
        return response() -> json($productData);
    }

    public function create()
    {
        if (!$this->userCan('crud-customer')) {
            abort(403);
        }
        $products = Product::all();
        return response() -> json($products);
    }

    public function store(Request $request){
        if (!$this->userCan('crud-customer')) {
            abort(403);
        }
        $request->validate([
            'image' => 'required|image',
            'product' => 'required|string|max:255',
            'price' => 'required|numeric',
        ]);
        $products = new Product();
        $products->product = $request->get('product');
        $products->intro = $request->get('intro');
        $products->price = $request->get('price');
        $products->price_sale = $request->get('price_sale');
        $products->quantity = $request->get('quantity');
        $products->badge = $request->get('badge');
        if ($request->hasFile('image')) {
            $file = $request->file('image');

            // Kiểm tra định dạng tệp
        if (in_array($file->getClientOriginalExtension(), ['jpg', 'jpeg', 'png', 'gif'])) {
            $path = $file->store('uploads', 'public');
            $products->image = $path;
        }
        else
        {
                return back()->withErrors(['image' => 'File không hợp lệ.']);
        }
        }

        $products->save();
        // add nhiều ảnh, gọi sau save để lấy product_id
        if ($request->hasFile('addimage')) {
            foreach ($request->file('addimage') as $file) {
            if (in_array($file->getClientOriginalExtension(), ['jpg', 'jpeg', 'png', 'gif'])) {
                $path = $file->store('uploads', 'public');

                $products->extraImages()->create([
                'image' => $path
            ]);
        } else {
                return back()->withErrors(['image' => 'File không hợp lệ.']);
                }
            }
        }

        // size
        if ($request->has('sizes')) {
            foreach ($request->sizes as $sizeData) {
                $products->sizes()->create([
                'size' => $sizeData['size'],
                'quantity' => $sizeData['quantity'],
            ]);
            }
        }
        // Cập nhật tổng quantity
        $products->quantity = $products->sizes()->sum('quantity');
        $products->save();

       return response()->json([
        'message' => 'Sản phẩm đã được tạo thành công',
        'product' => $products], 201);
    }



    function edit(Request $request,$id)
    {
        if (!$this->userCan('crud-customer')) {
            abort(403);
        }
        $products = Product::with('extraImages', 'sizes')->findOrFail($id);
        // Trả ra ảnh phụ là mảng đường dẫn
        $productData = $products->toArray();
        $productData['addimage'] = array_map(fn($img) => $img['image'], $products->extraImages->toArray());
        // thêm để hiện size
        $productData['sizes'] = array_map(fn($s) => $s['size'], $products->sizes->toArray());
        // thêm để hiện quantity
        $productData['size_quantities'] = array_map(fn($s) => $s['quantity'], $products->sizes->toArray());


        $productData['all_sizes'] = ['m', 'l', 'xl'];
        return response() -> json($productData);
    }

    public function destroy($id)
    {
          if (!$this->userCan('crud-customer')) {
            abort(403);
        }
        $products = Product::findOrFail($id);
        $image = $products->image;
        if ($image) {
            Storage::delete('/public/' . $image);
        }
         $products->delete();

         $source = request()->input('source', 'index');


        //  return redirect()->route('road.admin');
         return response() -> json([
            'message' => 'Sản phẩm đã được xóa thành công.',
            'deleted_product' => $products
         ]);
    }

    public function deleteExtraImage(Request $request)
    {
        if (!$this->userCan('crud-customer')) {
            abort(403);
        }
            $imagePath = $request->image;
            $image = ImagesProduct::where('product_id', $request->product_id)
                          ->where('image', $imagePath)
                          ->first();

        if ($image) {
        Storage::delete('public/' . $image->image); // Xoá file
        $image->delete(); // Xoá bản ghi
        return response()->json(['message' => 'Xoá ảnh phụ thành công']);
        }
    }

    public function update(Request $request, $id)
    {
         if (!$this->userCan('crud-customer')) {
            abort(403);
        }

        $products = Product::findOrFail($id);

        $validated = $request->validate([
            'product' => 'required|string|max:255',
            'intro' => 'nullable|string', // ✅ sửa từ longText -> string
            'price' => 'required|numeric', // ✅ sửa từ decimal -> numeric
            'price_sale' => 'nullable|numeric', // ✅
            'quantity' => 'nullable|numeric', // ✅
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048' // ✅ ảnh phải là image, không phải string
        ]);


        $products->product = $request->get('product');
        $products->intro = $request->get('intro');
        $products->price = $request->get('price');
        $products->price_sale = $request->get('price_sale');
        $products->quantity = $request->get('quantity');
        $products->badge = $request->get('badge');
        //cap nhat anh
        if ($request->hasFile('image')) {
            //xoa anh cu neu co
            $currentImg = $products->image;
            if ($currentImg) {
                Storage::delete('/public/' . $currentImg);
            }
            // cap nhat anh moi
            $image = $request->file('image');
            $path = $image->store('uploads', 'public');
            $products->image = $path;
        }

        $products->save();
        if ($request->hasFile('addimage')) {
            foreach ($request->file('addimage') as $file) {
            if (in_array($file->getClientOriginalExtension(), ['jpg', 'jpeg', 'png', 'gif'])) {
                $path = $file->store('uploads', 'public');

                $products->extraImages()->create([
                'image' => $path
            ]);
        } else {
                return back()->withErrors(['image' => 'File không hợp lệ.']);
                }
            }
        }

        $products->sizes()->delete();

        if ($request->has('sizes')) {
            foreach ($request->sizes as $sizeData) {
                $products->sizes()->create([
                'size' => $sizeData['size'],
                'quantity' => $sizeData['quantity'],
            ]);

            }
        }

        $products->quantity = $products->sizes()->sum('quantity');
        $products->save();

        return response()->json([
        'message' => 'Sản phẩm đã được tạo thành công',
        'product' => $products], 201);
    }
}
