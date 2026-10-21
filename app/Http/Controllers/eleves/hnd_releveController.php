<?php namespace gesecole\Http\Controllers\eleves;

use gesecole\cycle;
use gesecole\ReleveNoteGeneration;
use Illuminate\Http\Request;

class hnd_releveController extends bts_releveAnnuelController
{
    protected $transcriptType = 'hnd';
    protected $transcriptService = 'gesecole\Services\HndAnnualTranscript';

    protected function prepareTranscriptRows($rows, $semester, $classe)
    {
        $department = $classe->nom.' '.(!empty($classe->nom_anglais) ? $classe->nom_anglais : '');
        return \gesecole\Services\HndAnnualTranscript::presentationRows($rows, $semester, $department);
    }

    protected function availableCycles()
    {
        return cycle::whereRaw('UPPER(nom) LIKE ?', array('%HND%'))
            ->orWhereRaw('UPPER(nom) LIKE ?', array('%HIGHER NATIONAL DIPLOMA%'))->get();
    }

    public function history(Request $request, $idClasse = null)
    {
        $query = ReleveNoteGeneration::where('type', 'hnd')->orderBy('created_at', 'desc');
        if ($idClasse) $query->where('id_classe', (int) $idClasse);
        return view('eleves/releves_hnd_historique', array('titre'=>'Historique des relevés HND', 'generations'=>$query->get()));
    }

    public function printSaved($id)
    {
        $generation = ReleveNoteGeneration::where('type', 'hnd')->findOrFail($id);
        $payload = json_decode($generation->contenu, false);
        if (!$payload || !isset($payload->modele) || $payload->modele !== 'hnd_annuel') abort(422, 'Format HND invalide.');
        $values = (array) $payload;
        // Apply presentation corrections to saved generations without changing their snapshot.
        foreach ($values['PV'] as $transcript) {
            foreach ($transcript->semestres as $position => $semester) {
                $semester->rows = $this->prepareTranscriptRows($semester->rows,
                    isset($semester->numero) ? $semester->numero : $position, $values['classe']);
            }
        }
        $values['generation'] = $generation;
        $signature = \Input::get('date_signature');
        if ($signature) {
            if (!is_string($signature)) abort(422, 'Date de signature invalide.');
            $date = \DateTime::createFromFormat('!Y-m-d', $signature);
            if (!$date || $date->format('Y-m-d') !== $signature) abort(422, 'Date de signature invalide.');
            $values['date_signature'] = $signature;
        }
        return view('eleves/print_releves_hnd', $values);
    }

    public function destroy($id)
    {
        ReleveNoteGeneration::where('type', 'hnd')->findOrFail($id)->delete();
        return redirect()->to('/eleves/releves_hnd/historique')->with('success', 'Historique du relevé HND supprimé.');
    }
}
