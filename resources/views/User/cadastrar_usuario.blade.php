@extends('layouts.app')

@section('content')
    <div class="container-main">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8" id="div-conteudo">
                    <div class="container" id="div-conteudo-container">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="row" id="div-coluna1-logo">
                                    <div class="col-md-12" id="div-logo">
                                        <img src="{{asset("images/logo_bussola_2.png")}}" alt="Orientação" width="100%"> 
                                    </div>
                                    <div class="col-md-12" id="div-texto">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</div>
                                </div>
                            </div>
                            <div class="col-md-6" id="div-coluna2">
                                <div class="col-md-12" id="cabecalho-cadastro-usuario">Cadastro</div>
                                <form action="{{route('cadastrarUsuario.salvar')}}" method="POST">
                                    @csrf
                                    <div class="form-group" id="div-campo-nome">
                                        <label for="name">Nome <span class="cor-obrigatorio">(obrigatório)</span></label>
                                        <input type='text' class="form-control campos-cadastro @error('name') is-invalid @enderror" placeholder = "Digite seu nome" name='name' id='name' value="{{old('name')}}"/>    
                                        @error('name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{$message}}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group" >
                                        <label for="cpf">CPF <span class="cor-obrigatorio">(obrigatório)</span></label>
                                        <input type='text' class="form-control campos-cadastro @error('cpf') is-invalid @enderror" placeholder = "Digite seu CPF" name='cpf' id='cpf' value="{{old('cpf')}}"/>    
                                        @error('cpf')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{$message}}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="instituicao">Instituição <span class="cor-obrigatorio">(obrigatório)</span></label>
                                        <select class="form-control campos-cadastro @error('instituicao') is-invalid @enderror" name="instituicao" id="instituicao" value="{{old('instituicao')}}">
                                                <option value="" selected>Selecione sua instituição</option>

                                                @foreach ($instituicoes as $instituicao)
                                                    <option value="{{$instituicao->id}}">{{$instituicao->nome}}</option>
                                                @endforeach

                                                <option value="outros">Outros</option>
                        
                                                @error('instituicao')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{$message}}</strong>
                                                    </span>
                                                @enderror        
                                        </select>
                                    </div>
                                    <div class="form-group">
                                      <label for="email">E-mail <span class="cor-obrigatorio">(obrigatório)</span></label>
                                      <input type='text' class="form-control  campos-cadastro @error('email') is-invalid @enderror" placeholder = "Digite seu email" name='email' id='email' value="{{old('email')}}"/>    
                                        @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{$message}}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    {{-- <div class="form-group">
                                        <label for="email_confirmation">Confirmar e-mail <span class="cor-obrigatorio">(obrigatório)</span></label>
                                        <input type="email" class="form-control campos-cadastro @error('email_confirmation') is-invalid @enderror" id="email_confirmation" name="email_confirmation" placeholder="Confirme seu e-mail" value="{{old('email_confirmation')}}">
                                        @error('email_confirmation')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{$message}}</strong>
                                            </span>
                                        @enderror
                                    </div> --}}
                                    <div class="form-group">
                                        <label for="password">Senha <span class="cor-obrigatorio">(obrigatório)</span></label>
                                        <input type="password" class="form-control campos-cadastro @error('password') is-invalid @enderror" id="password" name="password" placeholder="Digite sua senha">
                                        @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{$message}}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="password_confirmation">Confirmar senha <span class="cor-obrigatorio">(obrigatório)</span></label>
                                        <input type="password" class="form-control campos-cadastro @error('password_confirmation') is-invalid @enderror" id="password_confirmation" name="password_confirmation" placeholder="Confirme sua senha">
                                        @error('password_confirmation')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{$message}}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="telefone_primario">Telefone primário <span class="cor-obrigatorio">(obrigatório)</span></label>
                                        <input type='text' class="form-control campos-cadastro @error('telefone_primario') is-invalid @enderror" placeholder = "Digite seu telefone primário" name='telefone_primario' id='telefone_primario' value="{{old('telefone_primario')}}"/>  
                                        @error('telefone_primario')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{$message}}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="telefone_secundario">Telefone secundário <span class="cor-obrigatorio">(opcional)</span></label>
                                        <input type='text' class="form-control campos-cadastro @error('telefone_secundario') is-invalid @enderror" placeholder = "Digite seu telefone primário" name='telefone_secundario' id='telefone_secundario' value="{{old('telefone_secundario')}}"/>  
                                        @error('telefone_secundario')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{$message}}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-check" id="div-lembrar-senha">
                                        <input type="checkbox" class="form-check-input campos-cadastro" id="lembrar_senha">
                                        <label class="form-check-label" for="lembrar_senha">Lembrar Senha </label>
                                    </div>
                                    <button type="submit" class="btn btn-success botoes-cadastro">Entrar</button>
                                    <hr>
                                    <a href="{{route('login')}}" class="btn btn-primary botoes-cadastro" >Voltar para a tela de login</a>
                                  </form>
                            </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- <form action="{{route('cadastrarUsuario.salvar')}}" method="POST"> 
            @csrf
            <br>
            <div class="row">
                <h1 class="col-md-1">Cadastro</h1>
            </div>
            <div class="row">
                <h2 class="col-md-6">Dados pessoais</h2>
            </div>
            <div class="row">
                <div class="col">
                    <div class="form-row">
                        <label for='name' class="col-md-1 col-form-label">Nome</label>
                    </div>
                    <input type='text' class="form-control @error('name') is-invalid @enderror" placeholder = "Digite seu nome" name='name' id='name' value="{{old('name')}}"/>    
                    @error('name')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{$message}}</strong>
                    </span>
                    @enderror
                </div>
                <div class="col">
                    <div class="form-row">
                        <label for='cpf' class="col-md-1 col-form-label">CPF</label>
                    </div>
                    <input type='text' class="form-control @error('cpf') is-invalid @enderror" placeholder = "Digite seu CPF" name='cpf' id='cpf' value="{{old('cpf')}}"/>    
                    @error('cpf')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{$message}}</strong>
                    </span>
                    @enderror
                </div>
                <div class="col">
                    <div class="form-row">
                        <label for='email' class="col-md-1 col-form-label">Email</label>
                    </div>
                    <input type='text' class="form-control @error('email') is-invalid @enderror" placeholder = "Digite seu email" name='email' id='email' value="{{old('email')}}"/>    
                    @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{$message}}</strong>
                    </span>
                    @enderror
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="form-row">
                        <label for='password' class="col-md-1 col-form-label">Senha</label>
                    </div>
                    <input type='password' class="form-control @error('password') is-invalid @enderror" placeholder = "Digite sua senha" name='password' id='password' value="{{old('password')}}"/>    
                    @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{$message}}</strong>
                    </span>
                    @enderror
                </div>

                <div class="col">
                    <div class="form-row">
                        <label for='password_confirmation' class="col-md-6 col-form-label">Confirme sua senha</label>
                    </div>
                    <input type='password' class="form-control @error('password_confirmation') is-invalid @enderror" placeholder = "Digite sua senha novamente" name='password_confirmation' id='password_confirmation'/>  
                    @error('password_confirmation')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{$message}}</strong>
                    </span>
                    @enderror
                </div>
                <div class="col">
                    <div class="form-row">
                        <label for='instituicao' class="col-md-6 col-form-label">Instituição</label>
                    </div>
                    <select name="instituicao" id="instituicao" class="form-control @error('instituicao') is-invalid @enderror" value="{{old('instituicao')}}"> 
                        <option selected>Selecione sua instituição</option>
                        @foreach ($instituicoes as $instituicao)
                            <option value="{{$instituicao->id}}">{{$instituicao->nome}}</option>
                        @endforeach
                        <option value="outros">Outros</option>

                    @error('instituicao')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{$message}}</strong>
                    </span>
                    @enderror        
                    </select>
                </div>
            </div>
            <br>
            <hr>
            <div class="row">
                <h2 class="col-md-6">Contato</h2>
            </div>
            <div class="row">
                <div class="col">
                    <div class="form-row">
                        <label for='telefone_primario' class="col-md-6 col-form-label">Telefone primário</label>
                    </div>
                    <input type='text' class="form-control @error('telefone_primario') is-invalid @enderror" placeholder = "Digite seu telefone primário" name='telefone_primario' id='telefone_primario' value="{{old('telefone_primario')}}"/>  
                    @error('telefone_primario')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{$message}}</strong>
                    </span>
                    @enderror
                </div>
                <div class="col">
                    <div class="form-row">
                        <label for='telefone_secundario' class="col-md-6 col-form-label">Telefone secundário</label>
                    </div>
                    <input type='text' class="form-control @error('telefone_secundario') is-invalid @enderror" placeholder = "Digite seu telefone secundário (opcional)" name='telefone_secundario' id='telefone_secundario' value="{{old('telefone_secundario')}}"/>  
                    @error('telefone_secundario')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{$message}}</strong>
                    </span>
                    @enderror
                </div>
                <div class="col"></div>
            </div>
            <br>
            <hr>
            <div class="row">
                <h2 class="col-md-6">Endereço (Opcional)</h2>
            </div>
            <div class="row">
                <div class="col">
                    <div class="form-row">
                        <label for='rua' class="col-md-1 col-form-label">Rua</label>
                    </div>
                    <input type='text' class="form-control @error('rua') is-invalid @enderror" placeholder = "Digite sua rua" name='rua' id='rua' value="{{old('rua')}}"/>  
                    @error('rua')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{$message}}</strong>
                    </span>
                    @enderror
                </div>
                <div class="col">
                    <div class="form-row">
                        <label for='bairro' class="col-md-1 col-form-label">Bairro</label>
                    </div>
                    <input type='text' class="form-control @error('bairro') is-invalid @enderror" placeholder = "Digite seu bairro" name='bairro' id='bairro' value="{{old('bairro')}}"/> 
                    @error('bairro')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{$message}}</strong>
                    </span>
                    @enderror
                </div>
                <div class="col">
                    <div class="form-row">
                        <label for='numero' class="col-md-1 col-form-label">Número</label>
                    </div>
                    <input type='text' class="form-control @error('numero') is-invalid @enderror" placeholder = "Digite seu número" name='numero' id='numero' value="{{old('numero')}}"/>  
                    @error('numero')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{$message}}</strong>
                    </span>
                    @enderror
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="form-row">
                        <label for='cep' class="col-md-1 col-form-label">CEP</label>
                    </div>
                    <input type='text' class="form-control @error('cep') is-invalid @enderror" placeholder = "Digite seu CEP" name='cep' id='cep' value="{{old('cep')}}"/>  
                    @error('cep')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{$message}}</strong>
                    </span>
                    @enderror
                </div>
                <div class="col">
                    <div class="form-row">
                        <label for='estado' class="col-md-1 col-form-label">Estado</label>
                    </div>
                    <select name="estado" id="estado" class="form-control @error('estado') is-invalid @enderror" value="{{old('estado')}}"> 
                        <option selected>Selecione seu estado</option>
                        <option>AC</option>
                        <option>AL</option> 
                        <option>AM</option>
                        <option>AP</option>   
                        <option>BA</option>   
                        <option>CE</option>   
                        <option>DF</option>   
                        <option>ES</option>   
                        <option>GO</option>   
                        <option>MA</option>   
                        <option>MG</option>   
                        <option>MS</option>   
                        <option>MT</option>   
                        <option>PA</option>   
                        <option>PB</option>   
                        <option>PE</option>   
                        <option>PI</option>
                        <option>PR</option>   
                        <option>RJ</option>   
                        <option>RN</option>   
                        <option>RO</option>   
                        <option>RR</option>   
                        <option>RS</option>   
                        <option>SC</option>   
                        <option>SE</option>   
                        <option>SP</option>
                        <option>TO</option>
                    @error('estado')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{$message}}</strong>
                    </span>
                    @enderror        
                    </select>
                </div>
                <div class="col">
                    <div class="form-row">
                        <label for='cidade' class="col-md-1 col-form-label">Cidade</label>
                    </div>
                    <input type='text' class="form-control @error('cidade') is-invalid @enderror" placeholder = "Digite sua cidade" name='cidade' id='cidade' value="{{old('cidade')}}"/>  
                    @error('cidade')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{$message}}</strong>
                    </span>
                    @enderror  
                </div>
            </div>
            <br>
            <hr>
            <div class="row">
                <div class="col-12">
                    <button id="botao-cadastrar" type='submit' class="btn btn-primary btn-block cor-botao" >Cadastrar</button>
                </div>
            </div>
        </form> --}}
    </div>
@endsection