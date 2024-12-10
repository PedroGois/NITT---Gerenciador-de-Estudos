# NITT - Gerenciador de Estudos

O **NITT** é uma aplicação desenvolvida para ajudar estudantes a organizarem suas matérias, atividades e priorizar tarefas de forma eficiente. Com funcionalidades como cadastro de atividades, descrição clara das tarefas, definição de prioridades e um timer Pomodoro integrado, o NITT promove foco e avaliação pós-estudo para melhorar continuamente a experiência de aprendizagem.

## Funcionalidades Principais

- **Cadastro de Matérias e Atividades**: Registre suas matérias e organize as tarefas relacionadas a elas.
- **Descrição de Atividades**: Escreva descrições claras e detalhadas para cada tarefa.
- **Definição de Prioridades**: Classifique suas atividades com diferentes níveis de prioridade para organizar sua rotina de estudos.
- **Pomodoro Timer**: Inicie sessões de Pomodoro para manter o foco durante seus estudos.
- **Avaliação Pós-Estudo**: Registre e analise seu desempenho após cada sessão.

## Tecnologias Utilizadas

- **Backend**: PHP
- **Frontend**: HTML, CSS, JavaScript
- **Banco de Dados**: MySQL
- **Servidor Local**: XAMPP

## Como Instalar no Windows

### Requisitos

- [XAMPP](https://www.apachefriends.org/index.html) instalado em seu computador.

### Passos de Instalação

1. **Clone ou Baixe o Repositório**

   - Baixe o código do repositório ou clone-o usando o comando:
     ```bash
     git clone <url-do-repositório>
     ```

2. **Configure o XAMPP**

   - Abra o XAMPP e inicie o **Apache** e o **MySQL**.

3. **Importe o Banco de Dados**

   - Acesse o [phpMyAdmin](http://localhost/phpmyadmin/).
   - Clique na aba SQL.
   - Copie o conteúdo do arquivo `script-dadosnitt.sql`, localizado na raiz do repositório, e cole na aba SQL do phpMyAdmin.
   - Execute o script para criar as tabelas e inserir os dados necessários.

4. **Configure o Projeto**

   - Copie os arquivos do projeto para a pasta `htdocs` do XAMPP.
   - Verifique se o arquivo de configuração do banco de dados (`model\conexao.php`) está apontando para:
     - Host: `localhost`
     - Usuário: `root`
     - Senha: (deixe em branco, a menos que tenha configurado uma senha no MySQL)

5. **Acesse o Sistema**

   - Abra o navegador e acesse:
     ```
     http://localhost/nitt
     ```

## Status do Projeto

O **NITT** está em desenvolvimento e pode crescer conforme nossa experiência e o feedback dos usuários. Já temos diversas ideias para melhorias, que serão implementadas no futuro. Este projeto foi desenvolvido como parte de nosso Trabalho de Conclusão de Curso (TCC) e será defendido em dezembro de 2024.

## Possíveis Melhorias Futuras

- **Integração com Aplicativos Mobile**
- **Notificações e Lembretes de Tarefas**
- **Estatísticas Avançadas de Desempenho**
- **Sincronização em Nuvem**

## Contribuições

Se você deseja contribuir com o projeto, fique à vontade para enviar pull requests ou relatar problemas na página do repositório.

## Licença

Este projeto é de uso educacional e pode ser adaptado para fins pessoais ou acadêmicos. Para outros usos, entre em contato conosco.

Email : pedromorais.gois@gmail.com
Linkedin : https://www.linkedin.com/in/pedro-gois-523344169/

---

Agradecemos por utilizar o NITT e esperamos que ele ajude a transformar sua experiência de estudos!

