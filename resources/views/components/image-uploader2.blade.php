<div>
    <div class="d-flex input-file-uploader">
        <span class="w-100 btn btn-success d-flex justify-content-center align-items-center" data-bs-toggle="modal"
            data-bs-target="#image-bank" data-bs-toggle="tooltip" data-bs-placement="top" title="Add Image"><i
                class="bi bi-upload mb-2"></i>&nbsp;&nbsp;
            Add Image</span>
        <input type="hidden" name="upload_image_selected" id="upload_image_selected" />
        {{-- <span class="border">OR</span>
        <div class="input-upload-file">
            <input type="file" name="upload_image" id="upload_image_button" />
            <span class="btn btn-succsess d-flex justify-content-center align-items-center"><i class="bi bi-upload mb-2"></i>
            Upload File</span>
        </div> --}}
    </div>
    <div class="image-file-preview mt-3" hidden>
        <img src="@if (!isset($item)) {{ asset('assets/images/preview-image.jpg') }} @else {{ Storage::url($item->image) }} @endif"
            alt="" srcset="" id="image_preview_result">
    </div>

    {{-- <div class="p-2 px-3 border mt-2">
        <p class="m-0" id="image_title">Lorem ipsum dolor sit amet consectetur, adipisicing elit. Ab officiis non architecto odit.</p>
    </div> --}}
    <hr>
    <div class="modal fade text-left " id="image-bank" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable d-flex justify-content-center modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="myModalLabel1">Image Bank</h5>
                    <button type="button" class="close rounded-pill" data-bs-dismiss="modal" aria-label="Close">
                        <i data-feather="x"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active" id="profile-tab" data-bs-toggle="tab" href="#profile"
                                role="tab" aria-controls="profile" aria-selected="true">Image Bank</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="home-tab" data-bs-toggle="tab" href="#home" role="tab"
                                aria-controls="home" aria-selected="true">Insert Image</a>
                        </li>
                    </ul>
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade" id="home" role="tabpanel" aria-labelledby="home-tab">
                            <div class="py-3 px-1" id="form-upload-image">
                                <div class="form-group">
                                    <label for="site_logo" class="mb-2">Image File</label>
                                    <div class="flex flex-column image-file-preview">
                                        <img src="{{ asset('assets/images/site_logo.png') }}" class="mb-3 image-preview"
                                            alt="Your Image" id="image_preview_modal">
                                        <input type="file" class="form-control" name="image_input"
                                            id="upload_image_button" accept="image/*" />
                                        @error('site_logo')
                                            <div class="invalid-feedback">
                                                <i class="bx bx-radio-circle"></i>
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="basicInput" class="mb-2">Title</label>
                                    <input type="text" class="form-control @error('title') is-invalid @enderror"
                                        id="basicInput" name="title_image" placeholder="Enter image title" />
                                    @error('title')
                                        <div class="invalid-feedback">
                                            <i class="bx bx-radio-circle"></i>
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="basicInput" class="mb-2">Image Alt</label>
                                    <input type="text" class="form-control @error('image_alt') is-invalid @enderror"
                                        id="basicInput" name="image_alt" placeholder="Enter image alt" />
                                    @error('image_alt')
                                        <div class="invalid-feedback">
                                            <i class="bx bx-radio-circle"></i>
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="site_logo" class="mb-2">Photographer</label>
                                    <div class="flex flex-column">
                                        <input type="text"
                                            class="form-control @error('photographer') is-invalid @enderror"
                                            id="basicInput" name="photographer"
                                            placeholder="Enter image photographer" />
                                        @error('photographer')
                                            <div class="invalid-feedback">
                                                <i class="bx bx-radio-circle"></i>
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="site_logo" class="mb-2">Copyright</label>
                                    <div class="flex flex-column">
                                        <div class="input-group mb-3">
                                            <span class="input-group-text" id="basic-addon1">&copy;
                                                {{ Date::now()->year }}</span>
                                            <input type="text"
                                                class="form-control @error('copyright') is-invalid @enderror"
                                                id="basicInput" name="copyright" placeholder="techno.id"
                                                value="" aria-describedby="basic-addon1" />
                                        </div>

                                        @error('copyright')
                                            <div class="invalid-feedback">
                                                <i class="bx bx-radio-circle"></i>
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="site_logo" class="mb-2">Caption</label>
                                    <div class="flex flex-column">
                                        <input type="text"
                                            class="form-control @error('caption') is-invalid @enderror"
                                            id="basicInput" name="caption" placeholder="Enter image caption" />
                                        @error('caption')
                                            <div class="invalid-feedback">
                                                <i class="bx bx-radio-circle"></i>
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="site_logo" class="mb-2">Keywords</label>
                                    <div class="flex flex-column">
                                        <textarea type="text" class="form-control @error('keywords') is-invalid @enderror" id="basicInput"
                                            name="keywords" placeholder="Enter keywords"></textarea>
                                        @error('keywords')
                                            <div class="invalid-feedback">
                                                <i class="bx bx-radio-circle"></i>
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="d-flex justify-content-end w-100">
                                    {{-- data-bs-dismiss="modal" --}}
                                    <button class="btn btn-primary" type="button"
                                        id="save_uploaded_image">
                                        <i class="bx bx-x d-block d-sm-none"></i>
                                        <span class="d-sm-block"><i class="bi bi-save"></i>&nbsp;&nbsp;Save
                                            Image</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade p-2 py-3 show active" id="profile" role="tabpanel"
                            aria-labelledby="profile-tab">
                            <x-image-bank />
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="bx bx-x d-block d-sm-none"></i>
                        <span class="d-none d-sm-block">Cancel</span>
                    </button>
                </div>

            </div>
        </div>
    </div>
</div>
