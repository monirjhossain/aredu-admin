<?php

namespace App\Http\Controllers\post;

use App\Http\Controllers\Controller;
use App\Http\Requests\Condominium\CondominiumRequest;
use App\Http\Requests\Post\PostRequest;
use App\Http\Requests\Condominium\CondominiumImportRequest;
use App\Imports\ComdominiumsImport;
use App\Models\condominium\Condominium;
use App\Models\installment\Installment;
use App\Models\Post\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Excel;

class PostController_old extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $auth = Auth::user();
        $posts = Post::query()->with(['manager'])
            ->searchable([
                [
                    'column_name' => 'id',
                    'type' => 'where',
                    'value' => request('id'),
                    'relation' => '',
                ],
                [
                    'column_name' => 'post_name',
                    'type' => 'like',
                    'value' => request('post_name'),
                    'relation' => '',
                ],
                [
                    'column_name' => 'iban',
                    'type' => 'like',
                    'value' => request('iban'),
                    'relation' => '',
                ],
                [
                    'column_name' => 'fiscal_code',
                    'type' => 'like',
                    'value' => request('fiscal_code'),
                    'relation' => '',
                ],
                [
                    'column_name' => 'address',
                    'type' => 'like',
                    'value' => request('address'),
                    'relation' => '',
                ],
                [
                    'column_name' => 'zip_code',
                    'type' => 'like',
                    'value' => request('zip_code'),
                    'relation' => '',
                ],
                [
                    'column_name' => 'city',
                    'type' => 'like',
                    'value' => request('city'),
                    'relation' => '',
                ],
                [
                    'column_name' => 'pec',
                    'type' => 'like',
                    'value' => request('pec'),
                    'relation' => '',
                ],
                [
                    'column_name' => 'sdi',
                    'type' => 'like',
                    'value' => request('sdi'),
                    'relation' => '',
                ],
                [
                    'column_name' => 'sia',
                    'type' => 'like',
                    'value' => request('sia'),
                    'relation' => '',
                ],
                [
                    'column_name' => 'country',
                    'type' => 'like',
                    'value' => request('country'),
                    'relation' => '',
                ],
                [
                    'column_name' => 'tenant_id',
                    'type' => 'like',
                    'value' => request('tenant_id'),
                    'relation' => '',
                ],
                [
                    'column_name' => 'vat',
                    'type' => 'like',
                    'value' => request('vat'),
                    'relation' => '',
                ],
                [
                    'column_name' => 'status',
                    'type' => 'where',
                    'value' => request('status'),
                    'relation' => '',
                ]
            ]);
        $postsCount = $posts->count();
        $posts = $posts->get()->toArray();
        $data = [];
        $data['data'] = $posts;
        $data['count'] = $postsCount;
        return $this->respond($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $managers = User::select('id', 'name', 'surname')->where('user_type_id', 4);
        if (Auth::user()->user_type_id != 1) {
            $managers->where('releted_to', Auth::id());
            $managers->orWhere('id', Auth::id());
        }

        $data = [
            'managers' => $managers->get(),
        ];
        return $this->respond($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PostRequest $request)
    {
        // return $request;
        DB::beginTransaction();
        try {
            $post = new Post();
            $postFillableData = $request->only($post->getFillable());
            $post->fill($postFillableData)->save();
            $post->post_author = Auth::user()->id;
            $post->post_type = 'post';
            $post->image = 'image';

            $post->save();
            DB::commit();
            return $this->respondCreated(trans('message.saved_successfully'), [
                'post' => $post,
            ]);
        } catch (ModelNotFoundException $e) {
            // DB::rollback();
            return $this->respondWithExpectationFailed('Sorry, Operation Failed');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        $managers = User::select('id', 'name', 'surname')->where('user_type_id', 4);
        if (Auth::user()->user_type_id != 1) {
            $managers->where('releted_to', Auth::id());
            $managers->orWhere('id', Auth::id());
        }
        try {
            $condominium = Condominium::whereId($id)->firstOrFail();
            $data = [
                'condominium' => $condominium,
                'managers' => $managers->get(),
            ];
            return $this->respond($data);
        } catch (\Throwable $th) {
            return $this->respondNotFound($th->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CondominiumRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            $condominium = Condominium::whereId($id)->firstOrFail();
            $condominiumFillableData = $request->only($condominium->getFillable());
            $condominium->fill($condominiumFillableData)->save();

            DB::commit();

            return $this->respondCreated(trans('message.updated_successfully'), [
                'condominium' => $condominium,
            ]);
        } catch (ModelNotFoundException $e) {
            DB::rollback();
            return $this->respondWithExpectationFailed('Sorry, Operation Failed');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $installments = Installment::where('condominium_id', $id)->get();
            if (count($installments) == 0) {
                $condominium = Condominium::whereId($id)->firstOrFail();
                if (
                    (!$condominium->condominiumSubscription()->exists() ||
                        ($condominium->condominiumSubscription()->exists() &&
                            $condominium->condominiumSubscription()->delete())) &&
                    $condominium->delete()
                ) {
                    DB::commit();
                    return $this->respond([
                        'status' => 1,
                        'message' => trans('message.deleted_successfully')
                    ]);
                } else {
                    DB::rollback();
                    return $this->respond([
                        'status' => 0,
                        'message' => trans('message.deleting_failed')
                    ]);
                }
            }
            return $this->respond([
                'status' => 0,
                'message' => trans('message.deleting_failed')
            ]);
        } catch (ModelNotFoundException $e) {
            return $this->respondNotFound('Sorry, Operation Failed');
        }
    }
    public function uploadFiles(CondominiumImportRequest $request)
    {
        try {
            Excel::import(new ComdominiumsImport($request), $request->file('file')->store('files'));
            return $this->respond([
                'status' => 1,
                'message' => "Importazione riuscita",
            ]);
        } catch (ModelNotFoundException $e) {
            return $this->respondNotFound('Sorry, Operation Failed');
        }
    }
}
