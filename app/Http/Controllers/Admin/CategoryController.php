<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;

use Illuminate\Http\Request;

class CategoryController extends Controller
{

    public function index()
    {

        $data = Category::paginate(PAGINATION_COUNT);

        return view('admin.categories.index', ['data' => $data]);
    }

    public function create()
    {
        if (auth()->user()->can('category-add')) {
            $categories= Category::get();

            return view('admin.categories.create',compact('categories'));
        } else {
            return redirect()->back()
                ->with('error', "Access Denied");
        }
    }



    public function store(Request $request)
    {

        try {
            $category = new Category();

            $category->name_en = $request->get('name_en');
            $category->name_ar = $request->get('name_ar');
            $category->description_en = $request->get('description_en');
            $category->description_ar = $request->get('description_ar');
            $category->in_home_screen = $request->get('in_home_screen');


            $parentCategoryID = $request->input('category_id');
            if ($parentCategoryID) {
                // Attach the parent category
                $parentCategory = Category::find($parentCategoryID);
                if ($parentCategory) {
                    $category->parentCategory()->associate($parentCategory);
                } else {
                    // Handle the case where the specified parent category does not exist
                    return redirect()->route('categories.index')->with('error', 'Parent category not found.');
                }
            }
            if ($request->has('photos')) {
                $photoPaths = [];
                if (is_array($request->photos)) {
                    foreach ($request->photos as $photo) {
                        if ($photo) {
                            $the_file_path = uploadImage('assets/admin/uploads', $photo);
                            $photoPaths[] = $the_file_path;
                        }
                    }
                } elseif ($request->photos) {
                    $the_file_path = uploadImage('assets/admin/uploads', $request->photos);
                    $photoPaths[] = $the_file_path;
                }

                if (!empty($photoPaths)) {
                    $category->photo = implode(',', $photoPaths);
                }
            }

            if ($category->save()) {


                return redirect()->route('categories.index')->with(['success' => 'Category created']);
            } else {
                return redirect()->back()->with(['error' => 'Something wrong']);
            }
        } catch (\Exception $ex) {
            return redirect()->back()
                ->with(['error' => 'عفوا حدث خطأ ما' . $ex->getMessage()])
                ->withInput();
        }
    }

    public function edit($id)
    {
        if (auth()->user()->can('category-edit')) {
            $data = Category::findorFail($id);
            $categories= Category::get();
            return view('admin.categories.edit', compact('data','categories'));
        } else {
            return redirect()->back()
                ->with('error', "Access Denied");
        }
    }

    public function update(Request $request, $id)
    {
        $category = Category::findorFail($id);
        try {

            $category->name_en = $request->get('name_en');
            $category->name_ar = $request->get('name_ar');
            $category->description_en = $request->get('description_en');
            $category->description_ar = $request->get('description_ar');
            $category->in_home_screen = $request->get('in_home_screen');


            $parentCategoryID = $request->input('category_id');
            if ($parentCategoryID) {
                // Attach the parent category
                $parentCategory = Category::find($parentCategoryID);
                if ($parentCategory) {
                    $category->parentCategory()->associate($parentCategory);
                } else {
                    // Handle the case where the specified parent category does not exist
                    return redirect()->route('categories.index')->with('error', 'Parent category not found.');
                }
            } else {
                // If no parent category is specified, disassociate from any existing parent
                $category->parentCategory()->dissociate();
            }

            if ($request->has('photos') && !empty($request->photos[0])) {
                $photoPaths = [];

                // Keep existing images if not being replaced
                if ($category->photo) {
                    $existingPaths = explode(',', $category->photo);
                    $photoPaths = array_merge($photoPaths, array_filter($existingPaths));
                }

                // Add new images
                if (is_array($request->photos)) {
                    foreach ($request->photos as $photo) {
                        if ($photo) {
                            $the_file_path = uploadImage('assets/admin/uploads', $photo);
                            $photoPaths[] = $the_file_path;
                        }
                    }
                } elseif ($request->photos) {
                    $the_file_path = uploadImage('assets/admin/uploads', $request->photos);
                    $photoPaths[] = $the_file_path;
                }

                if (!empty($photoPaths)) {
                    $category->photo = implode(',', array_unique($photoPaths));
                }
            }
            if ($category->save()) {
                return redirect()->route('categories.index')->with(['success' => 'Category update']);
            } else {
                return redirect()->back()->with(['error' => 'Something wrong']);
            }
        } catch (\Exception $ex) {
            return redirect()->back()
                ->with(['error' => 'عفوا حدث خطأ ما' . $ex->getMessage()])
                ->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $category = Category::findOrFail($id);

            // Delete the category
            if ($category->delete()) {
                return redirect()->back()->with(['success' => 'Category deleted successfully']);
            } else {
                return redirect()->back()->with(['error' => 'Something went wrong']);
            }
        } catch (\Exception $ex) {
            return redirect()->back()->with(['error' => 'Something went wrong: ' . $ex->getMessage()]);
        }
    }

}
