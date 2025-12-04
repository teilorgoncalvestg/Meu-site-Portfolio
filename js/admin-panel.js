// admin-panel.js
// Centraliza toda a lógica JS do painel administrativo

// Alternar edição dos títulos das seções
function toggleEditTitle(campo) {
    const btn = document.getElementById('btn-editar-title-' + campo);
    const input = document.getElementById('input-title-' + campo);
    const view = document.getElementById('view-title-' + campo);
    if (btn.textContent === 'Editar Título') {
        input.style.display = '';
        view.style.display = 'none';
        btn.textContent = 'Salvar Título';
        btn.style.background = '#0057d8';
        btn.style.color = '#fff';
        input.focus();
    } else {
        view.querySelector('h2, h3').textContent = input.value;
        input.style.display = 'none';
        view.style.display = '';
        btn.textContent = 'Editar Título';
        btn.style.background = '';
        btn.style.color = '';
    }
}

// Alternar seções do painel e fechar menu lateral
function selectSection(sec) {
    document.getElementById('section-perfil').style.display = (sec === 'perfil') ? 'block' : 'none';
    document.getElementById('section-projetos').style.display = (sec === 'projetos') ? 'block' : 'none';
    document.getElementById('section-servicos').style.display = (sec === 'servicos') ? 'block' : 'none';
    document.querySelector('.admin-sidebar').classList.remove('active');
}

// Inicializar CKEditor 5 nos campos de texto
const editorConfig = {
    toolbar: [
        'heading', '|', 'bold', 'italic', 'underline', 'strikethrough', 'link', 'imageUpload', 'blockQuote',
        'insertTable', 'mediaEmbed', 'undo', 'redo', 'numberedList', 'bulletedList', 'outdent', 'indent',
        'alignment', 'fontColor', 'fontBackgroundColor', 'fontSize', 'fontFamily', 'highlight', 'code', 'codeBlock',
        'removeFormat', 'horizontalLine', 'specialCharacters', 'subscript', 'superscript'
    ]
};
const editors = {};
function showEditor(campo) {
    document.getElementById('view-' + campo).style.display = 'none';
    document.getElementById('editor-' + campo).style.display = '';
    if (!editors[campo]) {
        ClassicEditor
            .create(document.querySelector('#editor-' + campo), editorConfig)
            .then(editor => {
                editor.ui.view.editable.element.style.minHeight = '120px';
                editor.ui.view.editable.element.style.maxHeight = '400px';
                editors[campo] = editor;
            })
            .catch(error => { console.error(error); });
    }
}
function hideEditor(campo) {
    if (editors[campo]) {
        editors[campo].destroy();
        editors[campo] = null;
    }
    document.getElementById('editor-' + campo).style.display = 'none';
    if (campo === 'web' || campo === 'prog') {
        var value = document.getElementById('editor-' + campo).value;
        value = value.replace(/<\/?ul>/gi, '').replace(/<\/?ol>/gi, '');
        document.getElementById('view-' + campo).innerHTML = '<ul>' + value + '</ul>';
    } else {
        document.getElementById('view-' + campo).innerHTML = document.getElementById('editor-' + campo).value;
    }
    document.getElementById('view-' + campo).style.display = '';
}

// Renderização dos projetos
function renderSlots() {
    fetch('listar_projetos.php')
        .then(r => r.json())
        .then(projetos => {
            const container = document.getElementById('lista-projetos');
            container.innerHTML = '';
            let idxNovo = projetos.length - 1;
            let slotHtml = `<div style='width:220px;height:260px;background:#fff;border:2px dashed #0057d8;border-radius:8px;display:flex;flex-direction:column;align-items:center;justify-content:center;cursor:pointer;margin-bottom:0.5em;' onclick='adicionarProjetoSlot(${idxNovo})'>`;
            slotHtml += `<span style='font-size:3em;color:#0057d8;'>+</span><span style='color:#0057d8;font-weight:bold;'>Adicionar Projeto</span></div>`;
            container.innerHTML += slotHtml;
            projetos.forEach((proj, idx) => {
                if (proj) {
                    let html = `<div style='width:220px;background:#f4f6fb;border-radius:8px;box-shadow:0 2px 8px rgba(0,0,0,0.07);padding:18px 12px;display:flex;flex-direction:column;align-items:center;'>`;
                    html += `<img src='${proj.capa ? proj.capa : 'conteudo/img/placeholder.png'}' alt='Capa' style='width:100%;height:120px;object-fit:cover;border-radius:6px;margin-bottom:12px;cursor:pointer;' onclick='editarProjeto(${idx})'>`;
                    html += `<strong>${proj.nome}</strong>`;
                    html += `<a href='${proj.link}' target='_blank' style='color:#0057d8;margin:8px 0 12px 0;word-break:break-all;'>Ver no GitHub</a>`;
                    html += `<button onclick='editarProjeto(${idx})' style='background:#0057d8;color:#fff;padding:6px 18px;border:none;border-radius:6px;cursor:pointer;margin-bottom:6px;'>Editar</button>`;
                    html += `<button onclick='abrirModalRemoverProjeto(${idx})' style='background:#e74c3c;color:#fff;padding:6px 18px;border:none;border-radius:6px;cursor:pointer;'>Remover</button>`;
                    html += `</div>`;
                    container.innerHTML += html;
                }
            });
        });
}
function editarProjeto(idx) {
    fetch('listar_projetos.php')
        .then(r => r.json())
        .then(projetos => {
            const p = projetos[idx];
            document.getElementById('form-projeto').style.display = 'block';
            document.getElementById('projeto-idx').value = idx;
            document.getElementById('projeto-nome').value = p.nome;
            document.getElementById('projeto-link').value = p.link;
            document.getElementById('projeto-capa').value = '';
        });
}
function adicionarProjetoSlot(idx) {
    document.getElementById('form-projeto').style.display = 'block';
    document.getElementById('projeto-idx').value = idx;
    document.getElementById('projeto-nome').value = '';
    document.getElementById('projeto-link').value = '';
    document.getElementById('projeto-capa').value = '';
}
function fecharFormProjeto() {
    document.getElementById('form-projeto').style.display = 'none';
}
let idxProjetoRemover = null;
let nomeProjetoRemover = '';
function abrirModalRemoverProjeto(idx) {
    fetch('listar_projetos.php')
        .then(r => r.json())
        .then(projetos => {
            idxProjetoRemover = idx;
            nomeProjetoRemover = projetos[idx]?.nome || '';
            document.getElementById('modal-remover-nome').textContent = nomeProjetoRemover;
            document.getElementById('modal-remover-projeto').style.display = 'flex';
        });
}
function fecharModalRemoverProjeto() {
    document.getElementById('modal-remover-projeto').style.display = 'none';
    idxProjetoRemover = null;
    nomeProjetoRemover = '';
}
function confirmarRemoverProjeto() {
    if (idxProjetoRemover === null) return;
    const form = new FormData();
    form.append('idx', idxProjetoRemover);
    fetch('remover_projeto.php', {
        method: 'POST',
        body: form
    }).then(() => {
        fecharModalRemoverProjeto();
        renderSlots();
    });
}

// Modal de foto de perfil
function openProfilePicModal() {
    document.getElementById('profilePicModal').style.display = 'flex';
}
function closeProfilePicModal() {
    document.getElementById('profilePicModal').style.display = 'none';
}

// Inicialização dos eventos
function initAdminPanel() {
    document.getElementById('btn-editar-title-sobre').onclick = function() { toggleEditTitle('sobre'); };
    document.getElementById('btn-editar-title-habilidades').onclick = function() { toggleEditTitle('habilidades'); };
    document.getElementById('btn-editar-title-formacao').onclick = function() { toggleEditTitle('formacao'); };
    document.getElementById('btn-editar-title-certificados').onclick = function() { toggleEditTitle('certificados'); };
    var btnEditTitleServicos = document.getElementById('btn-editar-title-servicos');
    if(btnEditTitleServicos) btnEditTitleServicos.onclick = function() {
        const btn = btnEditTitleServicos;
        const input = document.getElementById('input-title-servicos');
        const view = document.getElementById('view-title-servicos');
        if (btn.textContent === 'Editar Título') {
            input.style.display = '';
            view.style.display = 'none';
            btn.textContent = 'Salvar Título';
            btn.style.background = '#0057d8';
            btn.style.color = '#fff';
            input.focus();
        } else {
            view.querySelector('h2').textContent = input.value;
            input.style.display = 'none';
            view.style.display = '';
            btn.textContent = 'Editar Título';
            btn.style.background = '';
            btn.style.color = '';
        }
    };
    var btnEditTitleWeb = document.getElementById('btn-editar-title-web');
    if(btnEditTitleWeb) btnEditTitleWeb.onclick = function() {
        const btn = btnEditTitleWeb;
        const input = document.getElementById('input-title-web');
        const view = document.getElementById('view-title-web');
        if (btn.textContent === 'Editar Título') {
            input.style.display = '';
            view.style.display = 'none';
            btn.textContent = 'Salvar Título';
            btn.style.background = '#0057d8';
            btn.style.color = '#fff';
            input.focus();
        } else {
            view.querySelector('h3').textContent = input.value;
            input.style.display = 'none';
            view.style.display = '';
            btn.textContent = 'Editar Título';
            btn.style.background = '';
            btn.style.color = '';
        }
    };
    var btnEditTitleProg = document.getElementById('btn-editar-title-prog');
    if(btnEditTitleProg) btnEditTitleProg.onclick = function() {
        const btn = btnEditTitleProg;
        const input = document.getElementById('input-title-prog');
        const view = document.getElementById('view-title-prog');
        if (btn.textContent === 'Editar Título') {
            input.style.display = '';
            view.style.display = 'none';
            btn.textContent = 'Salvar Título';
            btn.style.background = '#0057d8';
            btn.style.color = '#fff';
            input.focus();
        } else {
            view.querySelector('h3').textContent = input.value;
            input.style.display = 'none';
            view.style.display = '';
            btn.textContent = 'Editar Título';
            btn.style.background = '';
            btn.style.color = '';
        }
    };
    // Botões de edição para Serviços
    ['web', 'prog'].forEach(function(campo) {
        var btn = document.getElementById('btn-editar-' + campo);
        if(btn) {
            btn.addEventListener('click', function() {
                var textarea = document.getElementById('editor-' + campo);
                if (btn.textContent.trim() === 'Editar') {
                    textarea.style.display = 'none'; // Oculta textarea
                    showEditor(campo);
                    btn.textContent = 'Salvar';
                    btn.style.background = '#0057d8';
                    btn.style.color = '#fff';
                } else {
                    if (editors[campo]) {
                        editors[campo].updateSourceElement();
                        textarea.value = editors[campo].getData(); // Atualiza textarea
                    }
                    hideEditor(campo);
                    btn.textContent = 'Editar';
                    btn.style.background = '';
                    btn.style.color = '';
                }
            });
        }
    });
    // Botões de edição para Perfil
    ['sobre', 'habilidades', 'formacao', 'certificados'].forEach(function(campo) {
        var btn = document.getElementById('btn-editar-' + campo);
        if(btn) {
            btn.addEventListener('click', function() {
                var textarea = document.getElementById('editor-' + campo);
                if (btn.textContent.trim() === 'Editar') {
                    textarea.style.display = 'none'; // Oculta textarea
                    showEditor(campo);
                    btn.textContent = 'Salvar';
                    btn.style.background = '#0057d8';
                    btn.style.color = '#fff';
                } else {
                    if (editors[campo]) {
                        editors[campo].updateSourceElement();
                        textarea.value = editors[campo].getData(); // Atualiza textarea
                    }
                    hideEditor(campo);
                    btn.textContent = 'Editar';
                    btn.style.background = '';
                    btn.style.color = '';
                }
            });
        }
    });
    var btnEditServicos = document.getElementById('btn-editar-servicos');
    if(btnEditServicos) btnEditServicos.onclick = function() { showEditor('servicos'); };
    document.getElementById('menu-projetos').addEventListener('click', renderSlots);
    selectSection('perfil');
    var sidebar = document.querySelector('.admin-sidebar');
    var btnHamburger = document.getElementById('btn-hamburger');
    btnHamburger.onclick = function(e) {
        console.log('Botão hambúrguer clicado');
        e.stopPropagation();
        sidebar.classList.toggle('active');
    };
    // Fecha o menu ao clicar fora dele (mobile)
    document.addEventListener('click', function(e) {
        if (window.innerWidth <= 800 && !sidebar.contains(e.target) && !btnHamburger.contains(e.target)) {
            sidebar.classList.remove('active');
        }
    });
    // Modal de foto de perfil
    function vincularModalFoto() {
    var modal = document.getElementById('modal-foto');
    var btnAlterarFoto = document.getElementById('btn-alterar-foto');
    var inputFoto = document.getElementById('input-foto');
    var previewFoto = document.getElementById('preview-foto');
    var btnSalvarFoto = document.getElementById('btn-salvar-foto');
    var btnCancelarFoto = document.getElementById('btn-cancelar-foto');

    // Abrir modal ao clicar no botão
    if(btnAlterarFoto && modal) {
        btnAlterarFoto.addEventListener('click', function(e) {
            e.preventDefault();
            modal.style.display = 'flex';
        });
    }
    // Fechar modal ao clicar em cancelar ou fora do modal
    if(btnCancelarFoto && modal) {
        btnCancelarFoto.addEventListener('click', function(e) {
            e.preventDefault();
            modal.style.display = 'none';
            inputFoto.value = '';
            previewFoto.src = '';
            previewFoto.style.display = 'none';
        });
    }
    if(modal) {
        modal.addEventListener('mousedown', function(e) {
            if(e.target === modal) {
                modal.style.display = 'none';
                inputFoto.value = '';
                previewFoto.src = '';
                previewFoto.style.display = 'none';
            }
        });
    }
    // Preview da imagem
    if(inputFoto && previewFoto) {
        inputFoto.addEventListener('change', function(e) {
            var file = e.target.files[0];
            if(file) {
                var reader = new FileReader();
                reader.onload = function(ev) {
                    previewFoto.src = ev.target.result;
                    previewFoto.style.display = 'block';
                };
                reader.readAsDataURL(file);
            } else {
                previewFoto.src = '';
                previewFoto.style.display = 'none';
            }
        });
    }
    // Preview da imagem
    if(inputFoto && previewFoto) {
        inputFoto.addEventListener('change', function(e) {
            var file = e.target.files[0];
            if(file) {
                var reader = new FileReader();
                reader.onload = function(ev) {
                    previewFoto.src = ev.target.result;
                    previewFoto.style.display = 'block';
                };
                reader.readAsDataURL(file);
            } else {
                previewFoto.src = '';
                previewFoto.style.display = 'none';
            }
        });
    }
    }
    vincularModalFoto();
    // (Removido: fechamento de modal antigo ao trocar de seção)
}

document.addEventListener('DOMContentLoaded', function() {
  var btnAlterarFoto = document.getElementById('btn-alterar-foto');
  if(btnAlterarFoto) {
    btnAlterarFoto.onclick = function() {
      document.getElementById('profilePicModal').style.display = 'flex';
    };
  }
});

// Inicialização robusta do painel admin
function tryInitAdminPanel() {
    if (document.getElementById('btn-hamburger') && document.querySelector('.admin-sidebar')) {
        initAdminPanel();
    } else {
        setTimeout(tryInitAdminPanel, 100);
    }
}
document.addEventListener('DOMContentLoaded', tryInitAdminPanel);
