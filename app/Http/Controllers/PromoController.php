<?php
namespace App\Http\Controllers;

use App\Models\Promo;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Exports\PromoExport;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class PromoController extends Controller
{
    public function index()
    {
        $promos = Promo::latest()->paginate(10);
        return view('staff.promo.index', compact('promos'));
    }

    public function datatables () {
        $promos = Promo::query();
        return DataTables::of($promos)
        ->addIndexColumn()
        ->addColumn('btnActions', function($promo){
          $btnEdit = '<a href="'.route('staff.promo.edit', $promo['id']).'"class="btn btn-primary me-2">Edit</a>';
          $btnDelete = '<form action="'. route('staff.promo.destroy', $promo['id']). '" method="post">'.
         @csrf_field().
         @method_field('DELETE').'
        <button type="submit" class="btn btn-danger me-2">Hapus</button>
         </form>';
         return '<div class="d-flex">'. $btnEdit . $btnDelete . '</div>';
        })
        ->rawColumns(['btnActions'])
        ->make(true);
    }

    public function create()
    {
        return view('staff.promo.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'promo_code' => 'required|unique:promos,promo_code',
            'type'       => 'required|in:percent,rupiah',
            'discount'   => 'required|integer|min:1',
        ]);

        if($request-> type === 'percent' && $request->discount > 100) {
            return redirect()->back()
            ->withInput()
            ->with('error', 'Discount percent tidak boleh lebih dari 100');
        }

        $createData = Promo::create([
            'promo_code' => $request->promo_code,
            'type' => $request->type,
            'discount' => $request->discount,
            'actived' => 1
        ]);


        if($request-> type === 'rupiah' && $request->discount < 1000) {
            return redirect()->back()
            ->withInput()
            ->with('error', 'Discount percent tidak boleh kurang dari 1000');
        }





        return redirect()->route('staff.promo.index')
            ->with('success', 'Promo berhasil ditambahkan!');
    }

    public function edit(Promo $promo)
    {
        return view('staff.promo.edit', compact('promo'));
    }

    public function update(Request $request, Promo $promo)
    {
        $request->validate([
            'promo_code' => 'required|unique:promos,promo_code,' . $promo->id,
            'type'       => 'required|in:percent,rupiah',
            'discount'   => 'required|integer|min:1',
        ]);

        $promo->update($request->all());

        return redirect()->route('staff.promo.index')
            ->with('success', 'Promo berhasil diperbarui!');
    }

   public function destroy(Promo $promo)
{
    $promo->forceDelete();

    return redirect()->route('staff.promo.index')
        ->with('success', 'Promo berhasil dihapus!');
}

public function exportExcel() {
    $filename = 'data-promo.xlsx';
    return Excel::download(new PromoExport, $filename);
}

public function trash() {
    $promoTrash = Promo::onlyTrashed()->get();
    return view('staff.promo.trash', compact('promoTrash'));
}
public function restore($id) {
    $promo = Promo::onlyTrashed()->find($id);
    $promo->restore();
    return redirect()->route('staff.promo.index')->with('success', 'Berhasil mengembalikan data!');
}

public function deletePermanent($id) {
    $promo = Promo::onlyTrashed()->find($id);
    $promo->forceDelete();
    return redirect()->back()->with('success', 'Berhasil menghapus seutuhnya!');
}

}
