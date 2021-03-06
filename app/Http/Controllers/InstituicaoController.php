<?php

namespace App\Http\Controllers;

use App\Models\Instituicao;
use App\Models\TemplateAtividade;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class InstituicaoController extends Controller
{


    public function listarInstituicoes()
    {
        return view('Instituicao.listar', ["instituicoes" => Instituicao::all()]);
    }

    public function verInstituicao($id)
    {
        $instituicao = Instituicao::find($id);
        if ($instituicao) {
            return view('Instituicao.ver', ["instituicao" => $instituicao]);
        }

        return redirect()->back();
    }

    public function salvarTemplate(Request $request)
    {
        $request->validate([
            "instituicao_id" => 'required',
            "tipo" => "required",
            "titulo" => "required",
        ]);

        $template = new TemplateAtividade;
        $template->fill($request->all());
        $template->save();
        return redirect()->route('instituicao.ver', $template->instituicao_id);
    }


    public function salvarEditarTemplate(Request $request)
    {
        $request->validate([
            "template_id" => 'required',
            "tipo" => "required",
            "titulo" => "required",
            "arr_template" => "required",
        ]);

        if ($template = TemplateAtividade::find($request->template_id)) {
            $template->fill($request->all());
            $template->arr_template = json_decode($request->arr_template);
            $template->save();
        }
        return redirect()->back();
    }

    public function deletarTemplate(Request $request)
    {
        //TODO: verificar permissões

        $template = TemplateAtividade::find($request->template_id);
        if($template) {
            $template->delete();
        }
        return redirect()->route('instituicao.ver', $template->instituicao_id);
    }


    public function cadastroInstituicao()
    {
        return view('Instituicao.cadastrar_instituicao');
    }

    public function salvarCadastrarInstituicao(Request $request)
    {
        $entrada = $request->all();

        $messages = [
            'required' => 'O campo nome é obrigatório.',
            'min' => 'O campo :attribute deve ter no mínimo :min caracteres.',
            'max' => 'O campo :attribute deve ter no máximo :max caracteres.',
        ];

        $validator = Validator::make($entrada, Instituicao::$rules, $messages);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $instituicao = new Instituicao();
        $instituicao->nome = $entrada['nome'];
        $instituicao->save();

        return redirect()->route('instituicao.listar');
    }

    public function deletarInstituicao(Request $request) {
        $instituicao = Instituicao::find($request->instituicao_id);
        if($instituicao) {
            $instituicao->delete();
        }
        return redirect()->route('instituicao.listar');
    }


    public function salvarEditarInstituicao(Request $request) {
        $instituicao = Instituicao::find($request->instituicao_id);
        if($instituicao) {
            $instituicao->nome = $request->nome;
            $instituicao->save();
        }
        return redirect()->back();        
    }

    public function verTemplate($id) {
        $template = TemplateAtividade::find($id);
        if($template) {
            return view('Instituicao.template.ver', ["template" => $template]);
        }
        return redirect()->back();
    }

    public function novoTemplate($id_instituicao) {
        $instituicao = Instituicao::find($id_instituicao);
        if($instituicao) {
            return view('Instituicao.template.novo', ["instituicao" => $instituicao]);
        }
        return redirect()->back();
    }

    public function lista_gerentes() {
        return view('Instituicao.listar_gerentes', ["users" => User::where('gerente_instituicoes', 1)->get()]);
    }

    private function adicionar_remover_gerente(Request $request, $marcar_como) {
        $request->validate([
            'email' => 'required|exists:users,email'
        ]);

        $user = User::where('email', $request->email)->first();
        $user->gerente_instituicoes = $marcar_como;
        $user->save();
        return redirect()->back();                
    }

    public function salvar_adicionar_gerentes(Request $request) {
        return $this->adicionar_remover_gerente($request, true);
    }

    public function remover_gerente(Request $request) {
        return $this->adicionar_remover_gerente($request, false);
    }
    
}
