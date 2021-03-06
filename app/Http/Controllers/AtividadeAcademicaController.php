<?php

namespace App\Http\Controllers;

use App\Models\AtividadeAcademica;
use App\Models\AtividadeUsuario;
use App\Models\Papel;
use App\Models\Secao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Google_Client;
use Google_Service_Drive;
use Google_Service_Drive_DriveFile;

class AtividadeAcademicaController extends DriveController
{

    public function cadastroAtividade()
    {
        return view('AtividadeAcademica.cadastrar_atividade_academica');
    }

    public function listarAtividades()
    {
        $usuarioLogado = User::find(Auth::id());
        //Criação da pasta da atividade acadêmica no Google Drive do usuário logado
        if ($usuarioLogado->folder_id_minhas_atividades == 'root') {
            $folder_id_minhas_atividades = $this->createFolder('Orientação - Minhas atividades', 'root');
            $usuarioLogado->update([
                'folder_id_minhas_atividades' => $folder_id_minhas_atividades,
            ]);
        }
        return view('AtividadeAcademica.listar_atividades_academicas')->with([
            'atividadesUsuario' => $usuarioLogado->atividadesUsuario,
            'usuarioLogado' => $usuarioLogado,
        ]);
    }

    public function verAtividade($atividade_id)
    {
        $atividade = AtividadeAcademica::find($atividade_id);
        if ($atividade && $atividade->user_logado_leitor_ou_acima()) {
            return view('AtividadeAcademica.ver_atividade_academica', ['atividade' => $atividade]);
        }
    }

    public function verSecoes($atividade_id, $secao_atual = 0)
    {

        $atividade = AtividadeAcademica::find($atividade_id);

        if (!$atividade) {
            return redirect()->route('login');
        }

        if (!$atividade->user_logado_leitor_ou_acima()) {
            return redirect()->back();
        }

        $secao = Secao::find($secao_atual);
        if ((!$secao) && ($atividade->secoes->count() > 0)) {
            return redirect()->route("verAtividade.verSecoes", [$atividade_id, $atividade->secoes[0]]);
        }


        if ($atividade) {
            return view('AtividadeAcademica.secao.secoes', [
                'atividade' => $atividade,
                'secao' => $secao,
            ]);
        }
    }

    public function verArquivos($atividade_id)
    {
        $atividade = AtividadeAcademica::find($atividade_id);

        if (!$atividade || !$atividade->user_logado_leitor_ou_acima()) {
            return redirect()->back();
        }

        $arquivos = $this->listarArquivosPasta($atividade);

        return view('AtividadeAcademica.arquivos', ['atividade' => $atividade, 'arquivos' => $arquivos->reverse()]);

    }

    public function verPessoas($atividade_id)
    {
        $atividade = AtividadeAcademica::find($atividade_id);

        if (!$atividade || !$atividade->user_logado_leitor_ou_acima()) {
            return redirect()->back();
        }

        return view('AtividadeAcademica.pessoas', ['atividade' => $atividade]);

    }

    public function salvarCadastrarAtividade(Request $request)
    {
        $entrada = $request->all();

        $messages = [
            'required' => 'O campo :attribute é obrigatório.',
            'min' => 'O campo :attribute deve ter no mínimo :min caracteres.',
            'max' => 'O campo :attribute deve ter no máximo :max caracteres.',
            'date' => 'O campo :attribute está inválido.',
        ];

        $validator = Validator::make($entrada, AtividadeAcademica::$rules, $messages);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $usuarioLogado = User::find(Auth::id());

        $atividadeAcademica = new AtividadeAcademica();
        $atividadeAcademica->tipo = $entrada['tipo'];
        $atividadeAcademica->titulo = $entrada['titulo'];
        $atividadeAcademica->descricao = $entrada['descricao'];
        $atividadeAcademica->data_inicio = $entrada['data_inicio'];
        $atividadeAcademica->data_fim = $entrada['data_fim'];
        if(in_array('cor_card', $entrada)){
            $atividadeAcademica->cor_card = $entrada['cor_card'];
        }else{
            $atividadeAcademica->cor_card = "#F0D882";
        }
        $atividadeAcademica->folder_id = "none";
        $atividadeAcademica->user_id = $usuarioLogado->id;
        $atividadeAcademica->save();

        $usuarioLogado = User::find(Auth::id());
        $atividadeUsuario = new AtividadeUsuario();
        $atividadeUsuario->user_id = $usuarioLogado->id;
        $atividadeUsuario->atividade_academica_id = $atividadeAcademica->id;
        $atividadeUsuario->save();

        $papel = new Papel();
        $papel->nome = Papel::PROPRIETARIO;
        $papel->atividade_usuario_id = $atividadeUsuario->id;
        $papel->save();

        //OBS: Título deve ser único?
        $folder_id = $this->createFolder($atividadeAcademica->titulo, $usuarioLogado->folder_id_minhas_atividades);

        $atividadeAcademica->update(['folder_id' => $folder_id]);

        return redirect()->route('listarAtividades');
    }

    public function salvarEditarAtividade(Request $request, $atividade_id)
    {
        $entrada = $request->all();

        $atividadeAcademica = AtividadeAcademica::find($atividade_id);

        if (!$atividadeAcademica || !$atividadeAcademica->user_logado_proprietario()) {
            return redirect()->back();
        }

        //dd($atividadeAcademica);
        //dd($entrada);
        $messages = [
            'required' => 'O campo :attribute é obrigatório.',
            'min' => 'O campo :attribute deve ter no mínimo :min caracteres.',
            'max' => 'O campo :attribute deve ter no máximo :max caracteres.',
            'date' => 'O campo :attribute está inválido.',
        ];

        $validator = Validator::make($entrada, AtividadeAcademica::$rules, $messages);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $atividadeAcademica->update([
            'tipo' => $request->input('tipo'),
            'titulo' => $request->input('titulo'),
            'descricao' => $request->input('descricao'),
            'data_inicio' => $request->input('data_inicio'),
            'data_fim' => $request->input('data_fim'),
        ]);

        // dd($entrada['cor_card']);

        if ($request->input('cor_card')) {
            $atividadeAcademica->update([
                'cor_card' => $request->input('cor_card')
            ]);
        }

        return redirect()->route('listarAtividades');
    }

    public function deletarAtividade(Request $request) {
        //TODO: deletar mesmo?
        return redirect()->back();
    }
}
