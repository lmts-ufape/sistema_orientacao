<?php

namespace App\Http\Controllers;

use App\Models\AtividadeAcademica;
use App\Models\AtividadeUsuario;
use App\Models\Papel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PessoaController extends DriveController
{
    public function salvarAdicionarPessoa(Request $request, $atividade_id)
    {
        $pessoaAdicionada = User::where('email', '=', $request->input('email'))->get()->first();
        if ($pessoaAdicionada) {
            $usuarioLogado = User::find(Auth::id());
            if ($pessoaAdicionada->email != $usuarioLogado->email) {
                $atividadeAcademica = AtividadeAcademica::find($atividade_id);
                $atividadeUsuarioExiste = AtividadeUsuario::where('user_id', '=', $pessoaAdicionada->id)->where('atividade_academica_id', '=', $atividadeAcademica->id)->get()->first();

                if (!$atividadeAcademica->user_logado_proprietario()) {
                    return redirect()->back(); // Só o proprietario pode adicionar pessoas
                }

                if (!$atividadeUsuarioExiste) {
                    if ($atividadeAcademica) {
                        $atividadeUsuario = new AtividadeUsuario();
                        $atividadeUsuario->user_id = $pessoaAdicionada->id;
                        $atividadeUsuario->atividade_academica_id = $atividadeAcademica->id;
                        $atividadeUsuario->save();

                        $papelPessoaAdicionada = new Papel();
                        $papelPessoaAdicionada->nome = $request->input('papel');
                        $papelPessoaAdicionada->atividade_usuario_id = $atividadeUsuario->id;
                        $papelPessoaAdicionada->save();

                        //Concedendo permissão no drive
                        $role = '';
                        if($papelPessoaAdicionada->nome == Papel::EDITOR){
                            $role = 'writer';
                        }
                        else if($papelPessoaAdicionada->nome == Papel::LEITOR){
                            $role = 'commenter';
                        }
                        else if($papelPessoaAdicionada->nome == Papel::GERENTE_DE_CONTEUDO){
                            //$role = 'fileOrganizer';
                        }

                        return $this->grantPermission($role, $pessoaAdicionada, $atividadeAcademica);
                    }
                }
            }
        }

        return redirect()->back();
    }

    public function editarPessoa(Request $request, $atividade_id)
    {
        $atividade = AtividadeAcademica::find($atividade_id);
        if (
            $atividade
            && $atividade->user_logado_proprietario()
            && in_array($request->papel, Papel::PAPEIS)
        ) {
            $atividade_usuario = AtividadeUsuario::find($request->atividade_usuario_id);
            $atividade_usuario->papel->nome = $request->papel;
            $atividade_usuario->papel->save();
        }

        return redirect()->back();
    }

    public function removerPessoa(Request $request, $atividade_id)
    {
        $atividade = AtividadeAcademica::find($atividade_id);

        if (
            $atividade
            && $atividade->user_logado_proprietario()
        ) {
            $atividade_usuario = AtividadeUsuario::find($request->id_membro);

            if ($atividade_usuario->user_id == Auth::id()) {
                return redirect()->back();
            }

            $atividade_usuario->papel->delete();
            $atividade_usuario->delete();
        }

        return redirect()->back();
    }
}
