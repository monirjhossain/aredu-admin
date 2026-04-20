<div class="card-body">
    @include('includes.errors')
    <div class="row">
        <div class="col-lg-4 col-md-4 col-sm-12">
            <div class="form-group">
                <label for="study_id">Study Level</label>
                <select name="study_id" class="form-control" id="">
                    @foreach ($studies as $item)
                        <option value="{{$item->id}}" {{ old('role_id', $student->study_id ?? '') == $item->id ? 'selected' : '' }}>{{$item->name}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        
        <div class="col-lg-4 col-md-4 col-sm-12">
            <div class="form-group">
                <label for="preferred_country">Preferred Country</label>
                <select name="preferred_country" class="form-control"
                    id="">
                    <option value="China" {{ ($student->preferred_country ?? '') == 'China' ? 'selected' : ' '}}>China</option>
                    <option value="USA" {{ ($student->preferred_country ?? '') == 'USA' ? 'selected' : ' '}}>USA</option>
                    <option value="UK" {{ ($student->preferred_country ?? '') == 'UK' ? 'selected' : ' '}}>UK</option>
                    <option value="Italy" {{ ($student->preferred_country ?? '') == 'Italy' ? 'selected' : ' '}}>Italy</option>
                    <option value="Germany" {{ ($student->preferred_country ?? '') == 'Germany' ? 'selected' : ' '}}>Germany</option>
                    <option value="Canada" {{ ($student->preferred_country ?? '') == 'Canada' ? 'selected' : ' '}}>Canada</option>
                    <option value="Spain" {{ ($student->preferred_country ?? '') == 'Spain' ? 'selected' : ' '}}>Spain</option>
                    <option value="Japan" {{ ($student->preferred_country ?? '') == 'Japan' ? 'selected' : ' '}}>Japan</option>
                    <option value="Pakistan" {{ ($student->preferred_country ?? '') == 'Pakistan' ? 'selected' : ' '}}>Pakistan</option>
                    <option value="Kyrgyzstan" {{ ($student->preferred_country ?? '') == 'Kyrgyzstan' ? 'selected' : ' '}}>Kyrgyzstan</option>
                    <option value="Australia" {{ ($student->preferred_country ?? '') == 'Australia' ? 'selected' : ' '}}>Australia</option>
                </select>
            </div>
        </div>
        
        <div class="col-lg-4 col-md-4 col-sm-12">
            <div class="form-group">
                <label for="intake">Intake</label>
                {{-- <input type="text" name="study_level" value="{{ old('study_level') }}" class="form-control" placeholder="Type here"> --}}
                <select name="intake" class="form-control" id="">
                    @foreach ($intake as $item)
                        {{-- <option {{ old('intake', $student->intake ?? '') == $item ? 'selected' : '' }} value="{{$item}}">{{$item}}</option> --}}
                        <option {{str_contains($item, now()->year) ? 'selected' : ''}} value="{{$item}}">{{$item}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-12">
            {{-- <h4>Official Info</h4> --}}
            {{-- <hr> --}}
        </div>
        <div class="col-lg-4 col-md-4 col-sm-12">
            <div class="form-group">
                <label class="control-label" for="categories">Preferred Subjects</label>
                <div class="flex-wrap border p-3" style="height: 200px; overflow: scroll;">
                    @foreach($subjects as $item)
                    <div class="custom-control custom-checkbox" style="margin-right: 20px">
                        <input class="custom-control-input" name="subjects[]" type="checkbox" id="item{{ $item->id}}" value="{{ $item->id }}" @foreach($student->subjects ?? [] as $sub) @if($item->id == $sub->id) checked @endif @endforeach>
                        <label for="item{{ $item->id}}" class="custom-control-label">{{ $item->name }}</label>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-12">
            <div class="form-group">
                <label class="control-label" for="categories">Documents</label>
                <div class="flex-wrap border p-3" style="height: 200px; overflow: scroll;">
                    @foreach($documents as $item)
                    <div class="custom-control custom-checkbox" style="margin-right: 20px">
                        <input class="custom-control-input" name="documents[]" type="checkbox" id="docu{{ $item->id}}" value="{{ $item->id }}" @foreach($student->documents ?? [] as $it) @if($item->id == $it->id) checked @endif @endforeach>
                        <label for="docu{{ $item->id}}" class="custom-control-label">{{ $item->name }}</label>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-12">
            <div class="form-group">
                <label class="control-label" for="categories">Preferred Universities</label>
                <div class="flex-wrap border p-3" style="height: 200px; overflow: scroll;">
                    @foreach($universities as $university)
                    <div class="custom-control custom-checkbox" style="margin-right: 20px">
                        <input class="custom-control-input" name="universities[]" type="checkbox" id="university{{ $university->id}}" value="{{ $university->id }}" @foreach($student->universities ?? [] as $sub) @if($university->id == $sub->id) checked @endif @endforeach>
                        <label for="university{{ $university->id}}" class="custom-control-label">{{ $university->post_title }}</label>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        
        <div class="col-12">
            <h4>Student Info</h4>
            <hr>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-12">
            <div class="form-group">
                @if(old('last_name') && !$errors->has('last_name'))
                    <span class="text-success" style="font-size:18px;"><i class="fa fa-check-circle" style="color:red"></i></span>
                @endif
                <label for="last_name">Last Name <span style="color:red">*</span></label>
                <input type="text" name="last_name" value="{{ old('last_name', $student->last_name ?? '')}}" class="form-control @error('last_name') is-invalid @enderror" placeholder="Type here">
                @error('last_name')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-12">
            <div class="form-group">
                @if(old('first_name') && !$errors->has('first_name'))
                    <span class="text-success" style="font-size:18px;"><i class="fa fa-check-circle" style="color:red"></i></span>
                @endif
                <label for="first_name">First Name <span style="color:red">*</span></label>
                <input type="text" name="first_name" value="{{ old('first_name', $student->first_name ?? '') }}" class="form-control @error('first_name') is-invalid @enderror" placeholder="Type here">
                @error('first_name')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-12">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="text" name="email" value="{{ old('email', $student->email ?? '') }}"
                    class="form-control" placeholder="Type here">
            </div>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-12">
            <div class="form-group">
                @if(old('passport') && !$errors->has('passport'))
                    <span class="text-success" style="font-size:18px;"><i class="fa fa-check-circle" style="color:red"></i></span>
                @endif
                <label for="passport">Passport <span style="color:red">*</span></label>
                <input type="text" name="passport" value="{{ old('passport', $student->passport ?? '') }}" class="form-control @error('passport') is-invalid @enderror" placeholder="Type here">
                @error('passport')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-12">
            <div class="form-group">
                @if(old('mobile_no') && !$errors->has('mobile_no'))
                    <span class="text-success" style="font-size:18px;"><i class="fa fa-check-circle" style="color:red"></i></span>
                @endif
                <label for="mobile_no">Phone No <span style="color:red">*</span></label>
                <input type="text" name="mobile_no" value="{{ old('mobile_no', $student->mobile_no ?? '') }}" class="form-control @error('mobile_no') is-invalid @enderror" placeholder="Type here">
                @error('mobile_no')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-12">
            <div class="form-group">
                <label for="address">Address</label>
                <input type="text" name="address"
                    value="{{ old('address', $student->address ?? '') }}" class="form-control"
                    placeholder="Type here">
            </div>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-12">
            <div class="form-group">
                <label for="city">City</label>
                <input type="text" name="city" value="{{ old('city', $student->city ?? '') }}"
                    class="form-control" placeholder="Type here">
            </div>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-12">
            <div class="form-group">
                <label for="country">Country</label>
                <select name="country" class="form-control" id="">
                    <option value="Bangladesh" {{ ($student->country ?? '') == 'Bangladesh' ? 'selected' : ' '}}>Bangladesh</option>
                    <option value="India" {{ ($student->country ?? '') == 'India' ? 'selected' : ' '}}>India</option>
                    <option value="Pakistan" {{ ($student->country ?? '') == 'Pakistan' ? 'selected' : ' '}}>Pakistan</option>
                    <option value="Nepal" {{ ($student->country ?? '') == 'Nepal' ? 'selected' : ' '}}>Nepal</option>
                </select>
            </div>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-12">
            <div class="form-group">
                <label for="zip">ZIP Code</label>
                <input type="text" name="zip" value="{{ old('zip', $student->zip ?? '') }}"
                    class="form-control" placeholder="Type here">
            </div>
        </div>
        <div class="col-12">
            <h4>Last Education Qualification</h4>
            <hr>
        </div>
        @php
            // If editing, load existing educations, else empty array
            $educations = collect($student->educationQualifications ?? []);
        @endphp

        @foreach($educations as $education)
            <input type="hidden" name="educations[id][]" value="{{ $education->id }}">
             <div class="col-lg-4 col-md-4 col-sm-12">
                <div class="form-group">
                    @if(old('educations.education_level_id.' . $loop->index) && !$errors->has('educations.education_level_id.' . $loop->index))
                        <span class="text-success" style="font-size:18px;"><i class="fa fa-check-circle" style="color:red"></i></span>
                    @endif
                    <label for="country"><b>Degree <span style="color:red;font-size:18px;font-weight:bold;vertical-align:middle;">*</span></b></label>
                    <select name="educations[education_level_id][]" class="form-control @error('educations.education_level_id.' . $loop->index) is-invalid @enderror" id="">
                        <option value="">----Select----</option>
                        @foreach($education_levels as $item)
                            <option value="{{$item->id}}" {{$education->education_level_id == $item->id ? 'selected' : ''}}>{{$item->name}}</option>
                        @endforeach
                    </select>
                    @error('educations.education_level_id.' . $loop->index)
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-12">
                <div class="form-group">
                    <label for="attend_from"><b>Year Attended (From) <span style="color:red;font-size:18px;font-weight:bold;vertical-align:middle;">*</span></b></label>
                    <input type="date" value="{{$education->attend_from}}" id="datePicker" name="educations[attend_from][]" class="form-control" placeholder="Type here">
                </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-12">
                <div class="form-group">
                    <label for="email"><b>Year Attended (To) <span style="color:red;font-size:18px;font-weight:bold;vertical-align:middle;">*</span></b></label>
                    <input type="date" value="{{$education->attend_to }}" id="datePicker" name="educations[attend_to][]" class="form-control" placeholder="Type here">
                </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-12">
                <div class="form-group">
                    <label>Institution</label>
                    <input type="text" name="educations[institution][]" value="{{ old('educations.institution.' . $loop->index, $education->institution) }}" class="form-control" placeholder="Type here">
                </div>
            </div>

            <div class="col-lg-4 col-md-4 col-sm-12">
                <div class="form-group">
                    <label>Field of Study</label>
                    <input type="text" name="educations[group_subject][]"  value="{{ old('educations.group_subject.' . $loop->index, $education->group_subject) }}" class="form-control" placeholder="Type here">
                </div>
            </div>

            <div class="col-lg-4 col-md-4 col-sm-12">
                <div class="form-group">
                    @if(old('educations.result.' . $loop->index) && !$errors->has('educations.result.' . $loop->index))
                        <span class="text-success" style="font-size:18px;"><i class="fa fa-check-circle" style="color:red"></i></span>
                    @endif
                    <label><b>Result <span style="color:red;font-size:18px;font-weight:bold;vertical-align:middle;">*</span></b></label>
                    <input type="text" name="educations[result][]"  value="{{ old('educations.result.' . $loop->index, $education->result) }}"  class="form-control @error('educations.result.' . $loop->index) is-invalid @enderror" placeholder="Type here">
                    @error('educations.result.' . $loop->index)
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="col-lg-4 col-md-4 col-sm-12">
                <div class="form-group">
                    <label>Out Of</label>
                    <input type="text" name="educations[out_of][]"  value="{{ old('educations.out_of.' . $loop->index, $education->out_of) }}" class="form-control" placeholder="Type here">
                </div>
            </div>
        @endforeach

        @if($educations->isEmpty())
            <div class="col-lg-4 col-md-4 col-sm-12">
                <div class="form-group">
                    @if(old('educations.education_level_id.0') && !$errors->has('educations.education_level_id.0'))
                        <span class="text-success" style="font-size:18px;"><i class="fa fa-check-circle" style="color:red"></i></span>
                    @endif
                    <label for="country"><b>Degree <span style="color:red;font-size:18px;font-weight:bold;vertical-align:middle;">*</span></b></label>
                    <select name="educations[education_level_id][]" class="form-control @error('educations.education_level_id.0') is-invalid @enderror" id="">
                        <option value="">----Select----</option>
                        @foreach($education_levels as $item)
                            <option value="{{$item->id}}">{{$item->name}}</option>
                        @endforeach
                    </select>
                    @error('educations.education_level_id.0')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-12">
                <div class="form-group">
                    @if(old('educations.attend_from.0') && !$errors->has('educations.attend_from.0'))
                        <span class="text-success" style="font-size:18px;"><i class="fa fa-check-circle" style="color:red"></i></span>
                    @endif
                    <label for="first_name"><b>Year Attended (From) <span style="color:red;font-size:18px;font-weight:bold;vertical-align:middle;">*</span></b></label>
                    <input type="date" id="datePicker" name="educations[attend_from][]" class="form-control @error('educations.attend_from.0') is-invalid @enderror" placeholder="Type here">
                    @error('educations.attend_from.0')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-12">
                <div class="form-group">
                    @if(old('educations.attend_to.0') && !$errors->has('educations.attend_to.0'))
                        <span class="text-success" style="font-size:18px;"><i class="fa fa-check-circle" style="color:red"></i></span>
                    @endif
                    <label for="email"><b>Year Attended (To) <span style="color:red;font-size:18px;font-weight:bold;vertical-align:middle;">*</span></b></label>
                    <input type="date" id="datePicker" name="educations[attend_to][]" class="form-control @error('educations.attend_to.0') is-invalid @enderror" placeholder="Type here">
                    @error('educations.attend_to.0')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-12">
                <div class="form-group">
                    <label for="passport">Country</label>
                    <input type="text" name="educations[country][]" value="{{ old('country', $student->height ?? '') }}" class="form-control" placeholder="Type here">
                </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-12">
                <div class="form-group">
                    <label for="mobile_no">Institution</label>
                    <input type="text" name="educations[institution][]" value="{{ old('institution', $student->height ?? '') }}" class="form-control" placeholder="Type here">
                </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-12">
                <div class="form-group">
                    <label for="group_subject">Field of Study</label>
                    <input type="text" name="educations[group_subject][]" value="{{ old('group_subject', $student->height ?? '') }}" class="form-control" placeholder="Type here">
                </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-12">
                <div class="form-group">
                    @if(old('educations.result.0') && !$errors->has('educations.result.0'))
                        <span class="text-success" style="font-size:18px;"><i class="fa fa-check-circle" style="color:red"></i></span>
                    @endif
                    <label for="result"><b>Result <span style="color:red;font-size:18px;font-weight:bold;vertical-align:middle;">*</span></b></label>
                    <input type="text" name="educations[result][]" value="{{ old('result', $student->height ?? '') }}" class="form-control @error('educations.result.0') is-invalid @enderror" placeholder="Type here">
                    @error('educations.result.0')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-12">
                <div class="form-group">
                    <label for="out_of">Out Of</label>
                    <input type="text" name="educations[out_of][]" value="{{ old('out_of', $student->height ?? '') }}" class="form-control" placeholder="Type here">
                </div>
            </div>
        @endif

        @php
            $hasCsca = (int) old('has_csca', $student->has_csca ?? 0);
            if (!$hasCsca && (old('csca_math', $student->csca_math ?? '') || old('csca_physics', $student->csca_physics ?? '') || old('csca_chemistry', $student->csca_chemistry ?? ''))) {
                $hasCsca = 1;
            }
        @endphp
        <div class="col-12 mb-2">
            <a href="javascript:void(0)" id="toggleCscaSection" class="text-primary font-weight-bold">Do YOU have CSCA?</a>
            <input type="hidden" name="has_csca" id="has_csca_input" value="{{ $hasCsca }}">
        </div>
        <div class="col-12" id="cscaSection" style="display: {{ $hasCsca ? 'block' : 'none' }};">
            <div class="border rounded p-3 mb-3 bg-light">
                <h5 class="mb-3">CSCA backround</h5>
                <div class="row">
                    <div class="col-lg-4 col-md-4 col-sm-12">
                        <div class="form-group">
                            <label for="csca_math">Math Result</label>
                            <input type="text" name="csca_math" id="csca_math" value="{{ old('csca_math', $student->csca_math ?? '') }}" class="form-control @error('csca_math') is-invalid @enderror" placeholder="Type here">
                            @error('csca_math')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-12">
                        <div class="form-group">
                            <label for="csca_physics">Physics Result</label>
                            <input type="text" name="csca_physics" id="csca_physics" value="{{ old('csca_physics', $student->csca_physics ?? '') }}" class="form-control @error('csca_physics') is-invalid @enderror" placeholder="Type here">
                            @error('csca_physics')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-12">
                        <div class="form-group">
                            <label for="csca_chemistry">Chemistry Result</label>
                            <input type="text" name="csca_chemistry" id="csca_chemistry" value="{{ old('csca_chemistry', $student->csca_chemistry ?? '') }}" class="form-control @error('csca_chemistry') is-invalid @enderror" placeholder="Type here">
                            @error('csca_chemistry')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12">
            <h4>Other Info</h4>
            <hr>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-12">
            <div class="form-group">
                <label for="gender">Gender</label>
                <select name="gender" class="form-control" id="">
                    <option value="">------ Select ------</option>
                    <option value="Male" {{ ($student->gender ?? '') == 'Male' ? 'selected' : ''}}>Male</option>
                    <option value="Female" {{ ($student->gender ?? '') == 'Female' ? 'selected' : ''}}>Female</option>
                    <option value="Other" {{ ($student->gender ?? '') == 'Other' ? 'selected' : ''}}>Other</option>
                </select>
            </div>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-12">
            <div class="form-group">
                <label for="marital_status">Marital Status</label>
                <select name="marital_status" class="form-control" id="">
                    <option {{ ($student->marital_status ?? '') == 'Single' ? 'selected' : ' '}}>Single</option>
                    <option {{ ($student->marital_status ?? '') == 'Married' ? 'selected' : ' '}}>Married</option>
                </select>
            </div>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-12">
            <div class="form-group">
                <label for="height">Height (Inch)</label>
                <input type="text" name="height" value="{{ old('height', $student->height ?? '') }}" class="form-control" placeholder="Type here">
            </div>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-12">
            <div class="form-group">
                <label for="weight">Weight</label>
                <input type="text" name="weight" value="{{ old('weight', $student->weight ?? '') }}" class="form-control" placeholder="Type here">
            </div>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-12">
            <div class="form-group">
                <label for="blood">Blood Group</label>
                <input type="text" name="blood" value="{{ old('blood', $student->blood ?? '') }}" class="form-control" placeholder="Type here">
            </div>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-12">
            <div class="form-group">
                <label for="language_cer">Language Proficiency</label>
                <input type="text" name="language_cer" value="{{ old('language_cer', $student->language_cer ?? '') }}" class="form-control" placeholder="Ex. IELTS-6.5 | HSK-4">
            </div>
        </div>
        <div class="col-12">
            <h4>Parent Info</h4>
            <hr>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-12">
            <div class="form-group">
                <label for="father_name">Father's Name</label>
                <input type="text" name="father_name"
                    value="{{ old('father_name', $student->father_name ?? '') }}" class="form-control"
                    placeholder="Type here">
            </div>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-12">
            <div class="form-group">
                <label for="father_mobile_no">Phone No</label>
                <input type="text" name="father_mobile_no"
                    value="{{ old('father_mobile_no', $student->father_mobile_no ?? '') }}" class="form-control"
                    placeholder="Type here">
            </div>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-12">
            <div class="form-group">
                <label for="father_occoupation">Occupation</label>
                <input type="text" name="father_occoupation"
                    value="{{ old('father_occoupation', $student->father_occoupation ?? '') }}" class="form-control"
                    placeholder="Type here">
            </div>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-12">
            <div class="form-group">
                <label for="mother_name">Mother's Name</label>
                <input type="text" name="mother_name"
                    value="{{ old('mother_name', $student->mother_name ?? '') }}" class="form-control"
                    placeholder="Type here">
            </div>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-12">
            <div class="form-group">
                <label for="mother_mobile_no">Phone No</label>
                <input type="text" name="mother_mobile_no"
                    value="{{ old('mother_mobile_no', $student->mother_mobile_no ?? '') }}" class="form-control"
                    placeholder="Type here">
            </div>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-12">
            <div class="form-group">
                <label for="mother_occoupation">Occupation</label>
                <input type="text" name="mother_occoupation"
                    value="{{ old('mother_occoupation', $student->mother_occoupation ?? '') }}" class="form-control"
                    placeholder="Type here">
            </div>
        </div>
        <div class="col-12">
            <h4>Official Info</h4>
            <hr>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-12">
            <div class="form-group">
                <label for="resp_person_id">Assigened Empolyee</label>
                <select name="resp_person_id" class="form-control" id="">
                    <option value="">None</option>
                    @foreach($users as $item)
                        <option value="{{$item->id}}" {{ ($student->resp_person_id ?? '') == $item->id ? 'selected' : ''}}>{{$item->name}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-12">
            <div class="form-group">
                <label for="status">Office Status</label>
                <select name="status" class="form-control"  id="">
                    {{-- <option value="">None</option> --}}
                    @foreach($student_statuses as $item)
                        <option value="{{$item->id}}" {{ ($student->status ?? '') == $item->id ? 'selected' : ''}}>{{$item->name}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-12">
            <div class="form-group">
                <label for="office_status">Reference</label>
                <input type="text" name="ref" value="{{ old('ref', $student->ref ?? '') }}" class="form-control" placeholder="Type here">
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-12">
            <div class="form-group">
                <label for="senior_counselor">Senior Counselor</label>
                <select name="senior_counselor" class="form-control" id="">
                    <option value="">None</option>
                    @foreach($users as $item)
                        <option value="{{$item->id}}" {{ ($student->senior_counselor ?? '') == $item->id ? 'selected' : ''}}>{{$item->name}}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="col-lg-6 col-md-6 col-sm-12">
            <div class="form-group">
                <label for="junior_counselor">Junior Counselor</label>
                <select name="junior_counselor" class="form-control" id="">
                    <option value="">None</option>
                    @foreach($users as $item)
                        <option value="{{$item->id}}" {{ ($student->junior_counselor ?? '') == $item->id ? 'selected' : ''}}>{{$item->name}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="form-group">
                <label for="note">Note</label>
                <textarea name="note" id="" rows="1" class="form-control">{{old('note',  $student->note ?? '')}}</textarea>
            </div>
        </div>
    </div>
</div>
<div class="card-footer">
    <button type="submit" class="btn btn-lg btn-primary">Submit</button>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const toggleLink = document.getElementById('toggleCscaSection');
        const cscaSection = document.getElementById('cscaSection');
        const hasCscaInput = document.getElementById('has_csca_input');

        if (!toggleLink || !cscaSection || !hasCscaInput) {
            return;
        }

        toggleLink.addEventListener('click', function () {
            const isHidden = cscaSection.style.display === 'none' || cscaSection.style.display === '';
            cscaSection.style.display = isHidden ? 'block' : 'none';
            hasCscaInput.value = isHidden ? '1' : '0';
        });
    });
</script>