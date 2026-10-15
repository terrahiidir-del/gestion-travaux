<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Intervention;
use Illuminate\Http\Request;

class MobileInterventionController extends Controller
{
    public function index(Request $request)
    {
        return Intervention::with(['demande','technicien:id,name,prenom'])
            ->where('technicien_id',$request->user()->id)->orderBy('date')->orderBy('heure')->get();
    }

    public function show(Request $request, Intervention $intervention)
    {
        abort_unless($intervention->technicien_id === $request->user()->id, 403);
        return $intervention->load(['demande','technicien:id,name,prenom']);
    }

    public function cloturer(Request $request, Intervention $intervention)
    {
        abort_unless($intervention->technicien_id === $request->user()->id, 403);
        $data=$request->validate(['rapport'=>'required|string|max:2000']);
        $intervention->update(['rapport'=>$data['rapport']]);
        $intervention->demande?->update(['statut'=>'terminé']);
        return response()->json(['message'=>'Intervention clôturée.','intervention'=>$intervention->fresh('demande')]);
    }
}
