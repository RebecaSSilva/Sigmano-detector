Verificador de DNA

Esta aplicação oferece funcionalidades para verificar se uma sequência de DNA pertence a um ser humano ou a uma espécie fictícia chamada "sigmano". Além disso, mantém um histórico das análises realizadas.

Funcionalidades:
Verificação de DNA:

O usuário pode inserir uma sequência de DNA no formato 6 linhas x 6 colunas.
A aplicação analisa a sequência e fornece o resultado indicando se pertence a um ser humano ou a um "sigmano" baseado na sequência de arrays.
Se encontrar uma ou mais sequências de quatro letras iguais nas direções horizontais, verticais ou nas diagonais.

Histórico de Análises:

O sistema registra todas as análises realizadas.
Os resultados das análises anteriores podem ser visualizados no histórico.
Requisitos:
PHP
Composer
Laravel
Banco de dados MySQL
Instruções de Configuração:

git clone https://github.com/RebecaSSilva/Sigmano-detector.git
cd Sigmano-detector

composer install

Configure o Ambiente:

Crie um arquivo .env com as configurações do banco de dados (utilize o .env.example como referência).

Execute as Migrações:

php artisan migrate

Inicie o Servidor Local:

php artisan serve

Acesse a Aplicação:
Abra o navegador e acesse http://localhost:8000.

Uso da Aplicação:
Verificar DNA:

Preencha o formulário com a sequência de DNA desejada.
Clique no botão "Verificar" para obter o resultado.

Histórico de Análises:

Clique no botão "Histórico" para visualizar as análises anteriores.
Retorne ao verificador clicando no botão correspondente.
Testes Unitários:
Execute os testes unitários para garantir a integridade da aplicação:

php artisan test

![image](https://github.com/RebecaSSilva/Sigmano-detector/assets/102828612/6fa99b9f-fc40-468a-91b1-f9964d43440f)
![image](https://github.com/RebecaSSilva/Sigmano-detector/assets/102828612/4df7dee0-0be6-4c4d-b498-5fa7eca3cbcd)

