<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HwaPermission;
use App\Models\HwaRole;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class RoleController extends Controller
{
    protected $viewPath = 'admin.roles';

    /**
     * @var HwaRole
     */
    protected $role;

    /**
     * RoleController constructor.
     * @param HwaRole $hwaRole
     */
    public function __construct(HwaRole $hwaRole)
    {
        $this->role = $hwaRole;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View|Response
     */
    public function index()
    {
        if (!hwa_check_permission('view_role')) {
            abort(404);
        }

        $path = $this->viewPath;
        $results = $this->role
            ->whereNotIn('name', ['super_admin'])
            ->orderBy('id', 'asc')
            ->select(['id', 'title', 'description'])
            ->get();

        return view("{$path}.index")->with([
            'path' => $path,
            'results' => $results
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View|Response
     */
    public function create()
    {
        if (!hwa_check_permission('add_role')) {
            abort(404);
        }

        $path = $this->viewPath;
        return view("{$path}.form")->with([
            'path' => $path
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request)
    {
        if (!hwa_check_permission('add_role')) {
            abort(404);
        }

        $validator = validator($request->all(), [
            'title' => ['required', 'max:191', 'unique:roles,name'],
            'permissions' => ['required']
        ], [
            'title.required' => 'Tên chức vụ là trường bắt buộc.',
            'title.max' => 'Tên chức vụ có tối đa 191 ký tự.',
            'title.unique' => 'Chức vụ đã tồn tại.',
            'permissions.required' => 'Vui lòng chọn quyền.',
        ]);

        if ($validator->fails()) {
            hwa_notify_error($validator->getMessageBag()->first());
            return redirect()->back()->withErrors($validator)->withInput();
        } else {
            $inputInfo = [
                'name' => str_replace('-', '_', Str::slug(trim($request['title']))),
                'guard_name' => 'admin',
                'title' => $request['title']
            ];

            DB::beginTransaction();
            try {
                $role = $this->role->create($inputInfo);
                $role->syncPermissions($request['permissions']);
                DB::commit();
                hwa_notify_success("Thêm chức vụ thành công.");
                return redirect()->route("{$this->viewPath}.index");
            } catch (\Exception $exception) {
                DB::rollBack();
                Log::error($exception->getMessage());
                hwa_notify_error("Lỗi thêm chức vụ.");
                return redirect()->back()->withInput();
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param $id
     * @return Application|Factory|View|Response
     */
    public function edit($id)
    {
        if (!hwa_check_permission('edit_role') || !$result = $this->role->with(['permissions'])->find($id)) {
            abort(404);
        } else {
            $path = $this->viewPath;
            return view("{$path}.form")->with([
                'path' => $path,
                'result' => $result,
                'maxPermission' => HwaPermission::all()->count()
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param $id
     * @return RedirectResponse
     */
    public function update(Request $request, $id)
    {
        if (!hwa_check_permission('edit_role') || !$result = $this->role->with(['permissions'])->find($id)) {
            abort(404);
        } else {
            $validator = validator($request->all(), [
                'title' => ['required', 'max:191', 'unique:roles,name,' . $id],
                'permissions' => ['required']
            ], [
                'title.required' => 'Tên chức vụ là trường bắt buộc.',
                'title.max' => 'Tên chức vụ có tối đa 191 ký tự.',
                'title.unique' => 'Chức vụ đã tồn tại.',
                'permissions.required' => 'Vui lòng chọn quyền.',
            ]);

            if ($validator->fails()) {
                hwa_notify_error($validator->getMessageBag()->first());
                return redirect()->back()->withErrors($validator)->withInput();
            } else {
                $inputInfo = [
                    'name' => str_replace('-', '_', Str::slug(trim($request['title']))),
                    'guard_name' => 'admin',
                    'title' => $request['title']
                ];

                DB::beginTransaction();
                try {
                    $result->fill($inputInfo)->save();
                    $result->syncPermissions($request['permissions']);
                    DB::commit();
                    hwa_notify_success("Cập nhật chức vụ thành công.");
                    return redirect()->route("{$this->viewPath}.index");
                } catch (\Exception $exception) {
                    DB::rollBack();
                    Log::error($exception->getMessage());
                    hwa_notify_error("Lỗi cập nhật chức vụ.");
                    return redirect()->back()->withInput();
                }
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return RedirectResponse
     */
    public function destroy($id)
    {
        if (!hwa_check_permission('delete_role') || !$result = $this->role->select(['id'])->find($id)) {
            abort(404);
        } else {
            if ($result->delete()) {
                hwa_notify_success("Xóa chức vụ thành công.");
            } else {
                hwa_notify_error("Lỗi xóa chức vụ.");
            }
            return redirect()->back();
        }
    }
}
