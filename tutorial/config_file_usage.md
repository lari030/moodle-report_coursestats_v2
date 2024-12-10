[Voltar](../README.md)

# Utilizando o arquivo de configuração

Este tutorial apresenta um exemplo de utilização do arquivo de configuração do plugin CourseStats e está disponível também em formato de [vídeo](https://www.youtube.com/watch?v=db7qCcLRKmI).

## 1. Introdução
- O arquivo de configuração permite personalizar as categorias e cursos exibidos no relatório estatístico do plugin CourseStats, sem alterar a organização
original do Moodle. Este guia detalha os passos para criar e configurar o arquivo de maneira eficiente.

## 2. Identificando o ID de uma categoria do Moodle
- Para identificar o ID de uma categoria no Moodle, primeiramente, acesse o menu "Cursos e Categorias".
- Depois, clique na categoria desejada e verifique o ID da categoria exibido na barra de endereços do seu navegador (Figura 1).

![Identificando o ID de uma categoria no Moodle](../images/tut4-1.png)

*Figura 1: Identificando o ID de uma categoria no Moodle.*

- No exemplo apresentado no vídeo que acompanha este tutorial, foram identificados os seguintes IDs:
    1. Categoria "EF - 1º Ano" → ID: 16
    2. Categoria "EF - 2º Ano" → ID: 17

## 3. Criando o arquivo de configuração
- Acesse o plugin através do menu "Relatórios". Depois, clique em "[Configurações] Estatísticas de Utilização de Cursos V2".
- No campo destinado ao arquivo de configuração, informe o texto abaixo e depois clique em "Salvar". Neste exemplo de arquivo de configuração, há apenas uma categoria, `EF - Anos Iniciais`, a qual é composta por todos os cursos das categorias do Moodle cujos IDs são 16 e 17. 

```
EF - Anos Iniciais:
16: *
17: * 
```
- Após salvar as modificações no arquivo de configuração, volte para o menu "Relatórios" e clique em "Estatísticas de Utilização de Cursos V2". Você verá um relatório personalizado com as categorias especificadas no arquivo de configuração (Figura 2).

![Relatório atualizado com base no arquivo de configuração](../images/tut4-2.png)

*Figura 2: Relatório atualizado com base no arquivo de configuração.*

## 4. Trabalhando com muitos cursos por categoria

- Conforme visto no tutorial ["Utilizando o plugin pela primeira vez"](first_usage.md), a categoria `Graduação` possui mais de 1600 cursos. Nestes casos, pode ser interessante para o administrador "dividir" os cursos dessa categoria em categorias menores, a fim de obter estatísticas mais apropriadas da utilização dos cursos da instituição. Como foi visto no tutorial ["Conhecendo a anatomia de um arquivo de configuração"](config_file_explanation.md), isso pode ser feito utilizando os filtros de cursos disponíveis no plugin.

- Considere o arquivo de configuração abaixo. Ele possui duas categorias, `Ciência da Computação` e `Administração`, as quais são compostas por cursos da categoria de ID 3 do Moodle (categoria "Graduação") cujos nomes breve terminam com "gcc" e "gae", respectivamente. Assim, é possível obter estatísticas de utilização de cursos por curso de graduação de uma instituição.

```
Ciência da Computação:
3: %gcc

Administração:
3: %gae
```
- Ao utilizar o arquivo de  configuração acima, o resultado será o relatório apresentado na Figura 3.

![Estatísticas de uso para os cursos de Ciência da Computação e Administração](../images/tut4-3.png)

*Figura 3: Estatísticas de uso para os cursos de Ciência da Computação e Administração.*


## 4. Considerações finais

-O arquivo de configuração do plugin oferece uma personalização poderosa, adaptando-se às necessidades específicas da instituição. 

- Com o arquivo de configuração do plugin CourseStats, é possível diversos tipos de análises, tais como: cursos de graduação vs. cursos de pós-graduação, cursos de humanas vs. cursos exatas, entre outros.

- Aqui se encerra o tutorial de instalação, configuração e uso do plugin CourseStats. Qualquer dúvida, estamos à disposição para ajudá-lo.