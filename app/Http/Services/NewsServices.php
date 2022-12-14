<?php

namespace App\Http\Services;

use Illuminate\Http\Request;

Interface NewsServices
{
    public function create();

    public function store(Request $request);

    public function show($id);
    
    public function edit($id);

    public function update(Request $request, $id);

    public function destroy($id);
}