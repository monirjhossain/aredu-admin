<div class="card-body card-block">
    <div class="row">
        <div class="col-lg-4 col-md-4 col-sm-12">
            <div class="form-group">
                <label for="name" class=" form-control-label">User Type <span class="text-danger">*</span></label>
                    <select name="role_id" class="form-control">
                    <option value="">None</option>
                    @foreach($roles as $item)
                    <option value="{{ $item->id }}" {{ old('role_id', $user->role_id ?? '') == $item->id ? 'selected' : '' }}>
                         {{-- {{ old('student_id', $financeEntry->student_id ?? '') == $student->id ? 'selected' : '' }} --}}
                        {{ $item->user_type }}
                    </option>
                    @endforeach
                </select>
                <span style="color: red"> {{ $errors->first('name') }} </span>
                
            </div>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-12">
            <div class="form-group">
                <label for="name" class=" form-control-label">Full Name <span class="text-danger">*</span></label>
                <input value="{{ old('name', $user->name ?? '') }}" type="text" id="name" name="name" placeholder="Full Name" class="form-control form-control-sm">
                <span style="color: red"> {{ $errors->first('name') }} </span>
                
            </div>
        </div>
        {{-- <div class="col-lg-4 col-md-4 col-sm-12">
            <div class="form-group">
                <label for="username" class=" form-control-label">Username <span class="text-danger">*</span></label>
                <input value="{{ old('username') }}" type="text" id="username" name="username" placeholder="Username" class="form-control form-control-sm">
                <span style="color: red">
                    {{ $errors->first('username') }}
                </span>
            </div>
        </div> --}}
        <div class="col-lg-4 col-md-4 col-sm-12">
            <div class="form-group">
                <label for="mobile_no" class=" form-control-label">Phone No </label>
                <input value="{{ old('mobile_no', $user->mobile_no ?? '') }}" type="text" name="mobile_no" id="mobile_no" placeholder="Phone No" class="form-control form-control-sm">
                <span style="color: red">
                    {{ $errors->first('mobile_no') }}
                </span>
            </div>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-12">
            <div class="form-group">
                <label for="email" class=" form-control-label">Email</label>
                <input value="{{ old('email', $user->email ?? '') }}" type="text" id="email" name="email" placeholder="Email" class="form-control form-control-sm">
                <span style="color: red">
                    {{ $errors->first('email') }}
                </span>
            </div>
        </div>
        @if(request())
        <div class="col-lg-4 col-md-4 col-sm-12">
            <div class="form-group">
                <label for="password" class=" form-control-label">Password <span class="text-danger">*</span></label>
                <input type="text" name="password" id="password" placeholder="password" class="form-control">
                <span style="color: red">
                    {{ $errors->first('password') }}
                </span>
            </div>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-12">
            <div class="form-group">
                <label for="name" class=" form-control-label">Related To <span class="text-danger">*</span></label>
                    <select name="related_to" class="form-control">
                    <option value="">None</option>
                    @foreach($users as $item)
                    <option value="{{ $item->id }}"{{ old('related_to', $user->related_to ?? '') == $item->id ? 'selected' : '' }}>
                        <!-- {{ $item->name ?? '' . ' - ' . $item->role->user_type ?? ''}} sdfsdfsdf -->
                        {{ $item->name ?? '' }} - {{ $item->role->user_type ?? '' }}
                    </option>
                    @endforeach
                </select>
                <span style="color: red"> {{ $errors->first('name') }} </span>
                
            </div>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-12">
            <div class="form-group">
                <label for="name" class=" form-control-label">Branch <span class="text-danger">*</span></label>
                    <select name="branch" class="form-control">
                    <option value="">None</option>
                    @foreach($branches as $item)
                    <option value="{{ $item->id }}" {{ old('branch', $user->branch ?? '') == $item->id ? 'selected' : '' }}>
                        {{ $item->name }}
                    </option>
                    @endforeach
                </select>
                <span style="color: red"> {{ $errors->first('name') }} </span>
                
            </div>
        </div>
        @endif
    </div>
    <div class="row">
        <div class="col-lg-12 mb-5">
            <div class="form-group row">
                <div class="col-lg-6 mb-5">
                    <label class=" form-control-label">Profile Picture<span class="text-danger">*</span></label>
                    <div class="form-group">
                        <label></label>
                        <div></div>
                        <div class="custom-file">
                            <input type="file" name="pro_pic" class="custom-file-input" id="customFile" />
                            <label class="custom-file-label" for="customFile">Select File</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-4 col-md-4 col-sm-12">
            <button type="submit" class="btn btn-success mr-2">Save</button>
        </div>
    </div>
</div>