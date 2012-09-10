v0.86
#descrição
Recuperação do commit 0.85

#changelog

#known issues
lentidão para abrir pop-up's com jquery/ajax
função jquery separada em arquivo externo "common.js" não está carregando

#todo
Implementar restante das opções modo "todos" (ver boleto, gerar segunda-via)
Finalizar detalhes dos pop-up's
Implementar jQuery/ajax para submeter formulário da página segunda-via
=====================================================================================
﻿v0.85
#descrição
Desenvolvimento dos pop-up's e aperfeiçoamento da interface do painel de boletos

#changelog
Implementado modal para todas as opções "excluir"
Iniciado desenvolvimento da página segunda-via
Implementada validação dos campos
Implementado resetar selects e checkboxes ao clicar em "cancelar" em qualquer pop-up

#known issues
lentidão para abrir pop-up's com jquery/ajax
função jquery separada em arquivo externo "common.js" não está carregando

#todo
Implementar restante das opções modo "todos" (ver boleto, gerar segunda-via)
Finalizar detalhes dos pop-up's
Implementar jQuery/ajax para submeter formulário da página segunda-via
=====================================================================================
v.075
#descrição
Desenvolvimento dos pop-up's e aperfeiçoamento da interface do painel de boletos 

#changelog
Melhora na interface: agora é mostrado o total de boletos ou clientes também, por exemplo: "Mostrando boletos 1 a 5 de 35" 
Implementada opção "enviar para cliente"
Implementada ordenação por serviço
Implementados ambos os pop'us 
Corrigido BUG: contagem de páginas no modo "clientes"

#known issues
lentidão para abrir pop-up's com jquery/ajax
função jquery separada em arquivo externo "common.js" não está carregando

#todo
Implementar restante das opções modo "todos" (ver boleto, gerar segunda-via)
Finalizar detalhes dos pop-up's
Iniciar página segunda-via
Limpar código após mudanças de lógica para uso de ajax com jquery

=====================================================================================
v0.7
#descrição
Desenvolvimento dos pop-up's e aperfeiçoamento da interface do painel de boletos 

#changelog
Implementado ajax com jquery para gerar pop-up's "ver dados pessoais" e "ver pedidos"
Criado esqueleto dos pop-up's
Corrigido BUG: css do cabeçalho da tabela quando ocupa 2 linhas a âncora fica menor que a célula
Corrigidos diversos conflitos de CSS com o tema

#known issues
lentidão para abrir pop-up's com jquery/ajax

#todo
Implementar restante das opções modo "todos" (ver boleto, gerar segunda-via, enviar para cliente)
Limpar código após mudanças de lógica para uso de ajax com jquery

=====================================================================================
v0.6
#descrição
Desenvolvimento do painel de boletos

#changelog
Implementado "excluir" no menu "quick-change" com modal
Implementado setas de ordenação no cabeçalho das tabelas
Modificados os estilos das tabelas
Modificado o dropdown de boletos/clientes por página: agora as opções variam de 5 em 5
Corrigidos diversos conflitos de CSS com o tema
Corrigido BUG: no modo "todos", visualizando resultados por cpf, ao tentar ordenar a tabela por qualquer seja o campo, perde-se a query var "cpf"

#known issues
BUG: css do cabeçalho da tabela quando ocupa 2 linhas a âncora fica menor que a célula

#todo
Implementar restante das opções modo "todos" (excluir, ver boleto, gerar segunda-via, enviar para cliente e ver pedido)
Implementar pop-up "ver dados pessoais" modo "clientes"
Limpar código após mudanças de lógica para uso de ajax com jquery

=====================================================================================
v0.5
28/08/12 - Renato
#descrição
Desenvolvimento do painel de boletos

#changelog
Semi-Implementadas opções modo "todos" (faltando excluir, ver boleto, gerar segunda-via, enviar para cliente e ver pedido)
Semi-implementada ordenação (faltando apenas ordenar por serviço)
Implementado "bulk actions" no rodapé
Implementado offset/limit
Implementado formatar data em PT_BR
Implementado colocar select box no “boleto por página”
Implementado select box opções
Implementado checar se é numérico campo “nosso número”
Implementado validar paginados
Implementado aparecer número da página atual
Resolvida questão legenda A/B
Corrigido “com marcado” centralizar
Corrigido bug css wp admin
Corrigido bug css "active" boletos
Corrigido bug: não está pegando valor no cabeçalho
Corrigido BUG: validação após pressionar botões do quadro “nosso número”
Corrigida margem do quadro de confirmação “nosso número”
Corrigida aparência do menu paginação
Corrigido padding td
Corrigido tirar “ok” bulk action
Corrigido centralizar texto do resumo de valores no topo
Corrigido centralizar botões do quadro “nosso número”
Corrigido table width
Iniciado e finalizado o desenvolvimento da interface do modo "clientes"


#known issues
BUG: no modo "todos", visualizando resultados por cpf, ao tentar ordenar a tabela por qualquer seja o campo, perde-se a query var "cpf"

#todo
Implementar restante das opções modo "todos" (excluir, ver boleto, gerar segunda-via, enviar para cliente e ver pedido)
Implementar pop-up "ver dados pessoais" modo "clientes"
Limpar código após mudanças de lógica para uso de ajax com jquery

=====================================================================================
v0.38
22/08/12 - Renato
#descrição
Familiarização com o código, criação da massa de testes e início do desenvolvimento do painel de boletos

#changelog
Implementada paginação
Consertado CSS - padding das células estava fazendo a tabela exceder a largura do elemento pai

#known issues
Tratar parâmetros GET com função do wordpress

#todo
Implementar opções (última coluna)

=====================================================================================
v0.35
21/08/12 - Renato
#descrição
Familiarização com o código, criação da massa de testes e início do desenvolvimento do painel de boletos

#changelog
Implementadas as query strings
Implementado resumo de boletos (pago, não pago, vencido)
Implementado 'quick-change' (input 'nosso número' com botões marcar como pago, não pago, etc)
Implementado modal
Melhorada a lógica para imprimir os valores dos boletos somados já no query do mysql evitando looping e otimizando consultas
Corrigida a lógica para recuperar os dados dos boletos somente depois de atualizações via 'quick-change'

#known issues
CSS - padding das células está fazendo a tabela exceder a largura do elemento pai

#todo
Implementar paginação
Implementar opções (última coluna)

=====================================================================================
v0.3
20/08/12 - Renato
#descrição
Familiarização com o código, criação da massa de testes e início do desenvolvimento do painel de boletos

#changelog
Corrigidos alguns erros de marcação da página de pedidos
Formatada e populada a tabela de boletos

#known issues
CSS - padding das células está fazendo a tabela exceder a largura do elemento pai

#todo
Implementar query strings
Implementar resumo de boletos (pago, não pago, vencido)
Implementar 'quick change' (input 'nosso número' com botões marcar como pago, não pago, etc)
Implementar paginação
Implementar opções (última coluna)

=====================================================================================
v0.2
20/08/12 - Francisco

#descrição
Estruturação da classe do plugin e seus recursos; promeiro formulário criado (pedido).

#changelog
Implementado custom post Serviço;
Implementado backend configurações globais;
Adaptado esqueleto original da classe para utilização das classes que servem de base para a construção de plugins;
Criado modelo de formulário na página "Pedido", usando parcialmente o bootstrap para estilização, masked input, css próprio em style-boletos.css;
Criado o modelo de dados no workbench e criada a respectiva tabela no banco wp_boletos1;
Programado mecanismo de url rewrite para a página "ver boleto" e "servicos", usando API do WP;
Acrescentada função get_ip em util.php;
Inclusos arquivos básicos para geração de boleto do Santander. Falta adaptação do código;
Escolhido template a ser usado em definitivo no site
>>> NOVOS ESTILOS DE CSS DEVEM SER COLOCADOS EM style-boletos.css <<<;

#known issues
vários ainda

#todo
vide linhas "//TODO" no source code
vide especificações (foram modificadas!)


=====================================================================================
v0.1 
12/08/12 - Francisco

#descrição
1st commit, com estrutura básica de pastas do WordPress 3.4.1 pt-BR.
Deve ser clonado para C:/AppServ/www (antes de clonar, configurar o local do clone no github for Windows).
Usuários criados: "admin", "trajettoria" e "usuario", "trajettoria".
Modificada estrutura de permalinks para "/%postname%/" e outras configurações gerais.
Deve ser criado manualmente um banco MySQL "wp_boletos1" em localhost, usuário "root", senha "krieger". O dump .sql localizado na pasta /wp-content/plugins/trajettoria-boletos/DEVELOPMENT deve ser importado ao banco recém criado.
Procurar usar este modelo de README.txt ao longo do desenvolvimento.
Mudar a minor version a cada commit no github.
>>>Antes de cada commit, criar um dump do banco de dados e armazenar em /DEVELOPMENT<<<
>>>Após fazer o download de uma nova versão a partir do github, importar o dump mais recente ao banco de dados, antes de retomar o desenvolvimento<<<
Explorar o arquivo /wp-content/plugins/trajettoria-boletos/includes/util.php antes de iniciar o desenvolvimento para conhecer algumas funções auxiliares que já foram disponibilizadas.


#changelog

#known issues

#todo
Vide backlog para orientações sobre o desenvolvimento.