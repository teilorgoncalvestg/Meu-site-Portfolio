<?php
session_start();
if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true) {
    header('Location: admin_login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <link rel="stylesheet" href="css/admin-panel.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" rel="stylesheet"/>
    <meta charset="UTF-8">
    <title>Painel Administrativo</title>
    <!-- CKEditor 5 Classic -->
    <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
    <script src="js/cropper-setup.js"></script>
    <script src="js/admin-panel.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
    @media (max-width: 768px) {
        /* FORÇA MOBILE LAYOUT - PRIORIDADE MÁXIMA */
        body {
            margin: 0 !important;
            padding: 0 !important;
            width: 100vw !important;
            overflow-x: hidden !important;
        }
        
        .admin-header {
            width: 100vw !important;
            margin: 0 !important;
            padding: 0 !important;
        }
        
        .admin-header > div {
            margin-left: 5px !important;
            margin-right: 5px !important;
        }
        
        
        /* FORÇA POSICIONAMENTO CORRETO */
        .admin-sidebar.admin-sidebar {
            top: 72px !important;
            position: fixed !important;
            margin: 0 !important;
            padding: 0 !important;
        }
        
        /* Links da sidebar em mobile */
        .admin-sidebar a {
            display: block !important;
            padding: 12px 16px !important;
            color: white !important;
            text-decoration: none !important;
            border-bottom: 1px solid rgba(255,255,255,0.1) !important;
            font-size: 0.9rem !important;
            margin: 0 !important;
        }
        
        /* HEADER FIXO PARA REFERÊNCIA */
        .admin-header {
            position: relative !important;
            z-index: 1000 !important;
            height: 72px !important;
            width: 100vw !important;
            overflow: hidden !important;
            padding: 0 !important;
            margin: 0 !important;
        }
        
        .admin-header > div[style*="margin-left:32px"] {
            margin-left: 8px !important;
            margin-right: 8px !important;
            gap: 8px !important;
        }
        
        /* TÍTULO RESPONSIVO APENAS MOBILE */
        .admin-header span[style*="font-size:2rem"] {
            font-size: 1.2rem !important;
            white-space: nowrap !important;
            overflow: hidden !important;
            text-overflow: ellipsis !important;
        }
        
        /* BOTÃO HAMBURGER */
        .admin-header .hamburger {
            flex-shrink: 0 !important;
            min-width: 36px !important;
        }
        
        .admin-content {
            margin: 0 !important;
            padding: 10px 5px !important;
            width: 100vw !important;
            box-sizing: border-box !important;
        }
        
        /* SOBRESCREVE TODOS OS ESTILOS INLINE */
        div[style] {
            margin-left: 5px !important;
        }
        
        div[style*="gap:32px"] {
            gap: 10px !important;
            flex-direction: column !important;
        }
        
        div[style*="margin-bottom:32px"] {
            margin-bottom: 15px !important;
        }
        
        span[style*="font-size:2rem"] {
            font-size: 1.2rem !important;
        }
    }
    </style>
    <script>
    // JavaScript para ajustar header responsive
    function ajustarHeaderResponsivo() {
        const header = document.querySelector('.admin-header');
        const headerDiv = header.querySelector('div');
        const headerSpan = header.querySelector('span');
        
        if (window.innerWidth <= 768) {
            // MOBILE - Aplicar estilos compactos
            document.body.style.margin = '0';
            document.body.style.padding = '0';
            
            if (header) {
                header.style.cssText = 'position:relative;display:flex;align-items:center;height:72px;background:#0057d8;padding:0;margin:0;width:100vw;overflow:hidden;box-sizing:border-box;';
            }
            
            if (headerDiv) {
                headerDiv.style.cssText = 'display:flex;align-items:center;gap:8px;margin-left:5px;margin-right:5px;width:calc(100% - 10px);box-sizing:border-box;padding-left:0;';
            }
            
            if (headerSpan) {
                headerSpan.style.cssText = 'color:white;font-size:1.2rem;font-weight:bold;font-family:sans-serif;letter-spacing:1px;line-height:1;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;';
            }
        } else {
            // DESKTOP - Restaurar estilos originais
            if (header) {
                header.style.cssText = 'position:relative;display:flex;align-items:center;height:72px;background:#0057d8;padding:0 0 0 0;';
            }
            
            if (headerDiv) {
                headerDiv.style.cssText = 'display:flex;align-items:center;gap:12px;margin-left:32px;';
            }
            
            if (headerSpan) {
                headerSpan.style.cssText = 'color:white;font-size:2rem;font-weight:bold;font-family:sans-serif;letter-spacing:1px;line-height:1;';
            }
        }
    }
    
    // Executa quando a página carrega e quando a janela é redimensionada
    window.addEventListener('load', ajustarHeaderResponsivo);
    window.addEventListener('resize', ajustarHeaderResponsivo);
    </script>
</head>
<body>
        <div class="admin-header" style="position:relative;display:flex;align-items:center;height:72px;background:#0057d8;padding:0 0 0 0;">
            <div style="display:flex;align-items:center;gap:12px;margin-left:32px;">
                <button class="hamburger" id="btn-hamburger" aria-label="Abrir menu" style="display:flex;align-items:center;justify-content:center;width:36px;height:36px;background:none;border:none;padding:0;margin:0 18px 0 0;cursor:pointer;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" fill="white" viewBox="0 0 24 24">
                        <path d="M3 6h18M3 12h18M3 18h18" stroke="white" stroke-width="2.5" stroke-linecap="round"/>
                    </svg>
                </button>
                <span style="color:white;font-size:2rem;font-weight:bold;font-family:sans-serif;letter-spacing:1px;line-height:1;">Painel Administrativo</span>
            </div>
            <a href="logout.php" class="logout-btn" style="margin-left:auto;">Sair</a>
        </div>
    <div class="admin-sidebar">
    <a href="#" id="menu-perfil" onclick="selectSection('perfil');return false;">Perfil</a>
    <a href="#" id="menu-projetos" onclick="selectSection('projetos');return false;">Projetos</a>
    <a href="#" id="menu-servicos" onclick="selectSection('servicos');return false;">Serviços</a>
    </div>
    <div class="admin-content">
        <!-- Seção Perfil -->
        <div id="section-perfil">
            <?php
            $foto_perfil = '';
            $usar_avatar_padrao = true;

            if (file_exists('conteudo/foto_perfil.txt')) {
                $foto_perfil = trim(file_get_contents('conteudo/foto_perfil.txt'));
                // Verifica se o arquivo da imagem realmente existe
                if ($foto_perfil && file_exists('conteudo/img/' . $foto_perfil)) {
                    $usar_avatar_padrao = false;
                }
            }
            ?>
            <div style="margin-bottom:32px;">
                <h2 class="profile-title">Foto de Perfil</h2>
                <div style="display:flex;align-items:center;gap:32px;">
                    <?php if ($usar_avatar_padrao): ?>
                        <div id="profile-pic" class="profile-avatar-default admin-avatar" style="width:120px;height:120px;border-radius:50%;border:4px solid #eee;box-shadow:0 2px 8px rgba(0,0,0,0.08);cursor:pointer;display:flex;align-items:center;justify-content:center;background:linear-gradient(135deg, #667eea 0%, #764ba2 100%);color:rgba(255,255,255,0.8);font-size:3rem;position:relative;overflow:hidden;">
                            <svg viewBox="0 0 24 24" fill="currentColor" style="width:60%;height:60%;filter:drop-shadow(0 2px 4px rgba(0,0,0,0.1));">
                                <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                            </svg>
                        </div>
                    <?php else: ?>
                        <img id="profile-pic" src="conteudo/img/<?php echo htmlspecialchars($foto_perfil); ?>?t=<?=time()?>" alt="Foto de perfil" style="width:120px;height:120px;border-radius:50%;object-fit:cover;border:4px solid #eee;box-shadow:0 2px 8px rgba(0,0,0,0.08);cursor:pointer;">
                    <?php endif; ?>
                    <button type="button" id="btn-alterar-foto" style="height:40px;">Alterar Foto</button>
                    <!-- Modal para alterar foto de perfil com Cropper.js -->
                    <div id="profilePicModal" style="display:none;position:fixed;top:0;left:0;width:100vw;height:100vh;background:rgba(0,0,0,0.4);z-index:9999;align-items:center;justify-content:center;">
                      <div style="background:#fff;padding:32px 24px;border-radius:12px;box-shadow:0 2px 16px rgba(0,0,0,0.2);width:370px;max-width:95vw;display:flex;flex-direction:column;align-items:center;position:relative;">
                        <button type="button" onclick="closeCropperModal()" style="position:absolute;top:10px;right:10px;background:transparent;border:none;font-size:1.6em;line-height:1;color:#888;cursor:pointer;">&times;</button>
                        <h3 style="margin-top:0;margin-bottom:16px;">Alterar Foto de Perfil</h3>
                        <input type="file" id="input-cropper" accept="image/*" style="margin-bottom:12px;">
                        <img id="cropper-preview" src="" alt="Preview" style="display:none;width:100%;height:auto;max-height:220px;border-radius:50%;margin-bottom:12px;">
                        <div id="cropper-controls" style="display:none;flex-direction:row;gap:12px;margin-bottom:8px;">
                          <button type="button" onclick="cropAndUpload()" style="background:#0057d8;color:#fff;padding:8px 16px;border:none;border-radius:6px;cursor:pointer;">Salvar</button>
                        </div>
                      </div>
                    </div>
                </div>
            </div>
            <form action="salvar_conteudo.php" method="post" style="margin-bottom:32px;">
                <?php
                function lerArquivo($caminho, $padrao = '') {
                    return file_exists($caminho) ? file_get_contents($caminho) : $padrao;
                }
                $titulo_sobre = lerArquivo('conteudo/titulo_sobre.txt', 'Sobre Mim');
                $titulo_habilidades = lerArquivo('conteudo/titulo_habilidades.txt', 'Habilidades');
                $titulo_formacao = lerArquivo('conteudo/titulo_formacao.txt', 'Formação');
                $titulo_certificados = lerArquivo('conteudo/titulo_certificados.txt', 'Certificados');
                $sobre = lerArquivo('conteudo/sobre.txt', 'Preencha seu texto sobre você...');
                $habilidades = lerArquivo('conteudo/habilidades.txt', '<ul><li>Exemplo de habilidade</li></ul>');
                $formacao = lerArquivo('conteudo/formacao.txt', '<ul><li>Exemplo de formação</li></ul>');
                $certificados = lerArquivo('conteudo/certificados.txt', '<ul><li>Exemplo de certificado</li></ul>');
                ?>
                <div class="editor-box">
                    <div style="display:flex;align-items:center;justify-content:space-between;">
                        <span id="view-title-sobre"><h2 class="sobre-title" style="margin-top:0;display:inline;"><?php echo htmlspecialchars($titulo_sobre); ?></h2></span>
                        <input type="text" id="input-title-sobre" name="titulo_sobre" value="<?php echo htmlspecialchars($titulo_sobre); ?>" style="display:none;font-size:1.3em;font-weight:bold;margin-top:0;" />
                        <button type="button" id="btn-editar-title-sobre" class="edit-btn">Editar Título</button>
                        <button type="button" id="btn-editar-sobre" class="edit-btn">Editar</button>
                    </div>
                    <div id="view-sobre"><?php echo $sobre; ?></div>
                    <textarea name="sobre" id="editor-sobre" style="display:none;" rows="4" cols="60"><?php echo $sobre; ?></textarea>
                </div>
                <div class="editor-box">
                    <div style="display:flex;align-items:center;justify-content:space-between;">
                        <span id="view-title-habilidades"><h3 style="margin-top:0;display:inline;"><?php echo htmlspecialchars($titulo_habilidades); ?></h3></span>
                        <input type="text" id="input-title-habilidades" name="titulo_habilidades" value="<?php echo htmlspecialchars($titulo_habilidades); ?>" style="display:none;font-size:1.1em;font-weight:bold;margin-top:0;" />
                        <button type="button" id="btn-editar-title-habilidades" class="edit-btn">Editar Título</button>
                        <button type="button" id="btn-editar-habilidades" class="edit-btn">Editar</button>
                    </div>
                    <div id="view-habilidades"><?php echo $habilidades; ?></div>
                    <textarea name="habilidades" id="editor-habilidades" style="display:none;" rows="3" cols="60"><?php echo $habilidades; ?></textarea>
                </div>
                <div class="editor-box">
                    <div style="display:flex;align-items:center;justify-content:space-between;">
                        <span id="view-title-formacao"><h3 style="margin-top:0;display:inline;"><?php echo htmlspecialchars($titulo_formacao); ?></h3></span>
                        <input type="text" id="input-title-formacao" name="titulo_formacao" value="<?php echo htmlspecialchars($titulo_formacao); ?>" style="display:none;font-size:1.1em;font-weight:bold;margin-top:0;" />
                        <button type="button" id="btn-editar-title-formacao" class="edit-btn">Editar Título</button>
                        <button type="button" id="btn-editar-formacao" class="edit-btn">Editar</button>
                    </div>
                    <div id="view-formacao"><?php echo $formacao; ?></div>
                    <textarea name="formacao" id="editor-formacao" style="display:none;" rows="2" cols="60"><?php echo $formacao; ?></textarea>
                </div>
                <div class="editor-box">
                    <div style="display:flex;align-items:center;justify-content:space-between;">
                        <span id="view-title-certificados"><h3 style="margin-top:0;display:inline;"><?php echo htmlspecialchars($titulo_certificados); ?></h3></span>
                        <input type="text" id="input-title-certificados" name="titulo_certificados" value="<?php echo htmlspecialchars($titulo_certificados); ?>" style="display:none;font-size:1.1em;font-weight:bold;margin-top:0;" />
                        <button type="button" id="btn-editar-title-certificados" class="edit-btn">Editar Título</button>
                        <button type="button" id="btn-editar-certificados" class="edit-btn">Editar</button>
                    </div>
                    <div id="view-certificados"><?php echo $certificados; ?></div>
                    <textarea name="certificados" id="editor-certificados" style="display:none;" rows="3" cols="60"><?php echo $certificados; ?></textarea>
                </div>
                <button type="submit" id="btn-salvar-tudo">Salvar Tudo</button>
                
            </form>
        </div>
        <!-- Seção Projetos -->
        <!-- Seção Serviços -->
        <div id="section-servicos" style="display:none;">
            <?php
            // Lê os títulos e textos dos arquivos de serviços
            $titulo_servicos = file_exists('conteudo/titulo_servicos.txt') ? trim(file_get_contents('conteudo/titulo_servicos.txt')) : 'Serviços';
            $titulo_web = file_exists('conteudo/titulo_web.txt') ? trim(file_get_contents('conteudo/titulo_web.txt')) : 'Serviços Web';
            $titulo_prog = file_exists('conteudo/titulo_prog.txt') ? trim(file_get_contents('conteudo/titulo_prog.txt')) : 'Serviços de Programação';
            $web = file_exists('conteudo/servicos_web.txt') ? file_get_contents('conteudo/servicos_web.txt') : '';
            $prog = file_exists('conteudo/servicos_prog.txt') ? file_get_contents('conteudo/servicos_prog.txt') : '';
            ?>
            <form action="salvar_conteudo.php" method="post" style="margin-bottom:32px;">
                <div class="editor-box">
                    <div style="display:flex;align-items:center;justify-content:space-between;">
                        <span id="view-title-servicos"><h2 class="sobre-title" style="margin-top:0;display:inline;"><?php echo $titulo_servicos; ?></h2></span>
                        <input type="text" id="input-title-servicos" name="titulo_servicos" value="<?php echo $titulo_servicos; ?>" style="display:none;font-size:1.3em;font-weight:bold;margin-top:0;" />
                        <button type="button" id="btn-editar-title-servicos" class="edit-btn">Editar Título</button>
                    </div>
                </div>
                <div class="editor-box">
                    <div style="display:flex;align-items:center;justify-content:space-between;">
                        <span id="view-title-web"><h3 style="margin-top:0;display:inline;"><?php echo $titulo_web; ?></h3></span>
                        <input type="text" id="input-title-web" name="titulo_web" value="<?php echo $titulo_web; ?>" style="display:none;font-size:1.1em;font-weight:bold;margin-top:0;" />
                        <button type="button" id="btn-editar-title-web" class="edit-btn">Editar Título</button>
                        <button type="button" id="btn-editar-web" class="edit-btn">Editar</button>
                    </div>
                    <div id="view-web"><?php echo $web; ?></div>
                    <textarea name="web" id="editor-web" style="display:none;" rows="6" cols="60"><?php echo $web; ?></textarea>
                </div>
                <div class="editor-box">
                    <div style="display:flex;align-items:center;justify-content:space-between;">
                        <span id="view-title-prog"><h3 style="margin-top:0;display:inline;"><?php echo $titulo_prog; ?></h3></span>
                        <input type="text" id="input-title-prog" name="titulo_prog" value="<?php echo $titulo_prog; ?>" style="display:none;font-size:1.1em;font-weight:bold;margin-top:0;" />
                        <button type="button" id="btn-editar-title-prog" class="edit-btn">Editar Título</button>
                        <button type="button" id="btn-editar-prog" class="edit-btn">Editar</button>
                    </div>
                    <div id="view-prog"><?php echo $prog; ?></div>
                    <textarea name="prog" id="editor-prog" style="display:none;" rows="6" cols="60"><?php echo $prog; ?></textarea>
                </div>
                <button type="submit">Salvar Serviços</button>
            </form>
        </div>
        <!-- Seção Projetos -->
        <div id="section-projetos" style="display:none;">
            <h2>Projetos</h2>
            <form id="form-projeto" action="salvar_projeto.php" method="post" enctype="multipart/form-data" style="margin-bottom:32px;display:none;">
                <input type="hidden" name="idx" id="projeto-idx">
                <label>Imagem de capa:</label><br>
                <input type="file" name="capa" id="projeto-capa" accept="image/*"><br>
                <label>Nome do projeto:</label><br>
                <input type="text" name="nome" id="projeto-nome" required><br>
                <label>Link do projeto (GitHub):</label><br>
                <input type="url" name="link" id="projeto-link" required><br>
                <button type="submit">Salvar Projeto</button>
                <button type="button" onclick="fecharFormProjeto()" style="margin-left:12px;background:#ccc;color:#333;">Cancelar</button>
            </form>
            <div id="lista-projetos" style="display:flex;flex-wrap:wrap;gap:24px;"></div>
    <!-- Modal para remover projeto -->
        <div id="modal-remover-projeto" style="display:none;position:fixed;top:0;left:0;width:100vw;height:100vh;background:rgba(0,0,0,0.4);z-index:9999;align-items:center;justify-content:center;">
            <div style="background:#fff;padding:32px 24px;border-radius:12px;box-shadow:0 2px 16px rgba(0,0,0,0.2);width:370px;max-width:95vw;display:flex;flex-direction:column;align-items:center;">
                <h3 style="margin-top:0;margin-bottom:16px;">Remover Projeto</h3>
                <p>Tem certeza que deseja remover o projeto <strong id="modal-remover-nome"></strong>?</p>
                <div style="display:flex;gap:16px;margin-top:16px;">
                    <button onclick="confirmarRemoverProjeto()" style="background:#e74c3c;color:#fff;padding:8px 24px;border:none;border-radius:6px;cursor:pointer;">Sim</button>
                    <button onclick="fecharModalRemoverProjeto()" style="background:#ccc;color:#333;padding:8px 24px;border:none;border-radius:6px;cursor:pointer;">Não</button>
                </div>
            </div>
        </div>
        <script>
// Garante que os valores dos títulos estejam atualizados antes de enviar o formulário
  document.addEventListener('DOMContentLoaded', function() {
    var form = document.querySelector('form[action="salvar_conteudo.php"]');
    if(form) {
      form.addEventListener('submit', function() {
        // Para cada título do perfil
        ['sobre','habilidades','formacao','certificados'].forEach(function(campo) {
          var input = document.getElementById('input-title-' + campo);
          var view = document.getElementById('view-title-' + campo);
          if(input && view) {
            // Sempre pega o texto visível
            var h = view.querySelector('h2,h3');
            if(h) input.value = h.textContent;
          }
        });
        // Para cada título dos serviços
        ['servicos','web','prog'].forEach(function(campo) {
          var input = document.getElementById('input-title-' + campo);
          var view = document.getElementById('view-title-' + campo);
          if(input && view) {
            var h = view.querySelector('h2,h3');
            if(h) input.value = h.textContent;
          }
        });
      });
    }
  });
</script>
</body>
</html>
