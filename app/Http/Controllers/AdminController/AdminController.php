<?php

namespace App\Http\Controllers\AdminController;

use App\Http\Controllers\Controller;
use App\Models\MainCategory;
use Illuminate\Http\Request;

class AdminController extends Controller
{
   public function login(){
    return view('Admin.Dashboard');
   }
   public function addcategory(){
    return view('Admin.add-category');
   }
   public function allcategory(){
      return view('Admin.allcategory');
   }
   public function editcategory($categoryId){
      $category = MainCategory::findOrFail($categoryId);
      return view('Admin.edit-category', compact('categoryId'));
   }
}
