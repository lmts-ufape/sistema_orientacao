<?php

namespace App\Http\Controllers;

use App\Models\AtividadeAcademica;
use App\Models\Secao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SecaoController extends Controller
{
    public function salvarAdicionarSecao(Request $request){
        $validator = Validator::make($request->all(), [
            'tipo' => 'required',
            'nome' => 'required',
            'atividade_academica_id' => 'required|exists:atividade_academicas,id',
            'secao_id' => 'nullable|exists:secaos,id'
        ]);

        $atividade = AtividadeAcademica::find($request->atividade_academica_id);
        if(!$atividade->user_logado_gerente_ou_acima()) {
            return redirect()->back();
        }

        if($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput()->with(["localizacao_erro" => "criar"]);
        }

        $nova_secao = new Secao;
        $nova_secao->fill($request->all());
        $nova_secao->secao_id = $request->secao_id;
        $nova_secao->save();
        $nova_secao = Secao::find($nova_secao->id);
        $nova_secao->ordem = $nova_secao->ordem + (2 * $nova_secao->id);
        $nova_secao->save();
        return redirect()->back();
    }

    public function salvarEditarSecao(Request $request){
        $validator = Validator::make($request->all(), [
            'tipo' => 'required',
            'nome' => 'required',
            'atividade_academica_id' => 'required|exists:atividade_academicas,id',
        ]);

        $atividade = AtividadeAcademica::find($request->atividade_academica_id);
        if(!$atividade->user_logado_gerente_ou_acima()) {
            return redirect()->back();
        }

        if($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput()->with(["localizacao_erro" => "editar"]);
        }

        $nova_secao = Secao::find($request->id_secao);
        $nova_secao->fill($request->all());
        $nova_secao->save();
        return redirect()->back();
    }

    public function deletarSecao(Request $request) {
        $sec = Secao::find($request->secao_id);

        if($sec) {
            if(!$sec->atividade->user_logado_proprietario()) {
                return redirect()->back();
            }
        }
        
        ($sec ? $sec->delete() : "");
        return redirect()->back();
    }

    public function salvar_ordenar_secao(Request $request) {
        $id_secao_arrastada = $request->id_secao_arrastada;
        $id_irmao_ante = $request->id_irmao_ante;
        $id_irmao_post = $request->id_irmao_post;

        if($id_secao_arrastada == $id_irmao_ante || $id_secao_arrastada == $id_irmao_post || $id_irmao_ante == $id_irmao_post) {
            return redirect()->back();
        }

        $secao_arrastada = Secao::find($id_secao_arrastada);

        if($secao_arrastada) {
            if(!$secao_arrastada->atividade->user_logado_gerente_ou_acima()) {
                return redirect()->back();
            }
        }
        
        

        if($id_secao_arrastada == 0 || !($secao_arrastada)) {
            return redirect()->back();
        }

        $irmao_ante = Secao::find($id_irmao_ante);
        $irmao_post = Secao::find($id_irmao_post);


        if($irmao_ante) {
            $id_secao_testada = $id_irmao_ante;
            $secao_atual = $irmao_ante;
            while ($secao_atual = $secao_atual->secao_pai()) {
                $id_secao_testada = $secao_atual->id;
                if($id_secao_testada == $id_secao_arrastada) {
                    return redirect()->back();
                }
            }
        }

        if($irmao_post) {
            $id_secao_testada = $id_irmao_post;
            $secao_atual = $irmao_post;
            while ($secao_atual = $secao_atual->secao_pai()) {
                $id_secao_testada = $secao_atual->id;
                if($id_secao_testada == $id_secao_arrastada) {
                    return redirect()->back();
                }
            }
        }


        if($irmao_ante && $irmao_post) {
            $id_secao_pai = $irmao_ante->secao_id;
            $secao_arrastada->secao_id = $id_secao_pai;
            $secao_arrastada->ordem = ($irmao_post->ordem + $irmao_ante->ordem) / 2.0;
            $secao_arrastada->save();
            return redirect()->back();
        }

        if($irmao_ante && !$irmao_post) {
            $id_secao_pai = $irmao_ante->secao_id;
            $secao_arrastada->secao_id = $id_secao_pai;
            $secao_arrastada->ordem = $irmao_ante->ordem + 2.0; // Pra que as divições sejam sempre números pares, facilita pro servidor
            $secao_arrastada->save();
            return redirect()->back();
        }

        if(!$irmao_ante && $irmao_post) {
            $id_secao_pai = $irmao_post->secao_id;
            $secao_arrastada->secao_id = $id_secao_pai;
            $secao_arrastada->ordem = $irmao_post->ordem - 2.0; // Pra que as divições sejam sempre números pares, facilita pro servidor
            $secao_arrastada->save();
            return redirect()->back();
        }
    }

    public function salvar_subsecionar_secao(Request $request) {
        $id_secao_alvo = $request->id_secao_alvo;
        $id_secao_movida = $request->id_secao_arrastada_que_vai_entrar;


        $secao_alvo = Secao::find($id_secao_alvo);
        $secao_movida = Secao::find($id_secao_movida);

        if((!$secao_alvo || !$secao_movida) || ($secao_alvo->id == $secao_movida->id)) {
            return redirect()->back();
        }

        if(!$secao_movida->atividade->user_logado_gerente_ou_acima()) {
            return redirect()->back();
        }

        if(!($secao_alvo->secao_id == $secao_movida->secao_id)) {

            $secao_atual = $secao_alvo;
            do {
                if($secao_atual->id == $secao_movida->id) {
                    return redirect()->back();
                }

                $secao_atual = $secao_atual->secao_pai();
            } while($secao_atual);
        }

        $secao_movida->secao_id = $secao_alvo->id;

        if($secao_alvo->secoes->count() > 0) {
            $secao_movida->ordem = $secao_alvo->secoes->last()->ordem + 2.0;
        } else {
            $secao_movida->ordem = 10000.0;
        }
        $secao_movida->save();
        return redirect()->back();

    }

    public function arvore_secao_html($id_atividade, $id_secao) {
        $atividade = AtividadeAcademica::find($id_atividade);

        if(!$atividade) {
            return redirect()->route('login');
        }

        
        $secao = Secao::find($id_secao);
        if((!$secao) && ($atividade->secoes->count() > 0)) {
            return redirect()->route("verAtividade.verSecoes", [$id_atividade, $atividade->secoes[0]]);
        }


        if($atividade){
            return view('AtividadeAcademica.secao.arvore_secoes', [
                'atividade' => $atividade,
                'secao' => $secao,
            ]);
        }

    }

}
