[Voltar](../README.md)

# Conhecendo a anatomia de um arquivo de configuração

Este tutorial apresenta a anatomia do arquivo de configuração do plugin CourseStats e está disponível também em formato de [vídeo](https://www.youtube.com/watch?v=b8SyizBfEIs).

## 1. O que é um arquivo de configuração?
- O arquivo de configuração é um arquivo de texto que permite organizar e personalizar a categorização de cursos dentro do plugin. Ele é utilizado para definir categorias e cursos, sem afetar a organização de cursos no Moodle. Este arquivo pode conter uma ou mais categorias e cada categoria pode ser associada a uma lista de cursos.


## 2. Estrutura de um arquivo de configuração

- O arquivo é composto por categorias e suas respectivas listas de cursos.

- Cada categoria no arquivo de configuração é identificada por um nome seguido pelo símbolo de dois pontos (`:`). O nome da categoria é livre e pode ser escolhido pelo usuário.

- Na linha abaixo de cada categoria, ficam as listas de cursos que pertencem àquela categoria. Cada lista de cursos é composta por: 
    1. O identificador da categoria no Moodle onde o curso se encontra, seguido pelo símbolo de dois pontos (`:`); e
    2. O nome breve do(s) curso(s), separados por vírugla (`,`). O nome breve pode ser informado por completo ou apenas uma parte dele, como será explicado adiante.

## 3. Exemplo de um arquivo de configuração

- Considere o arquivo de configuração abaixo. Ele é formato por duas categorias, `Matemática` e `Português`, e cada categoria é composta por dois cursos. Por exemplo, a `Matemática` é composta pelos cursos `1ef-mat` e `2ef-mat`, que advêm das categorias de ID 1 e 2 do Moodle. 

```
Matemática:
1: 1ef-mat
2: 2ef-mat

Português:
1: 1ef-lp
2: 2ef-lp
```

- Já o arquivo de configuração abaixo possui apenas uma categoria, `Primeiro Ano - EF`, que é composta por dois cursos, a saber, `1ef-mat` e `1ef-lp`, ambos pertencentes à categoria de ID 1 do Moodle. 

```
Primeiro Ano - EF:
1: 1ef-mat, 1ef-lp
```

## 4. Filtrando cursos de uma categoria

- O plugin permite simplificar a filtragem de cursos ao utilizar partes do nome breve do curso. Para isso, o símbolo de porcentagem (`%`) pode ser utilizado para omitir o restante do nome do curso. Por exemplo: 
    1. Para filtrar todos os cursos de uma categoria que terminam com "mat", você pode utilizar `%mat`.
    2. Para filtrar todos os cursos de uma categoria que começam com "mat", você pode utilizar `mat%`.
    3. Para filtrar todos os cursos de uma categoria que contenham a palavra "mat", você pode utilizar `%mat%`.

- É possível ainda adicionar todos os cursos de uma determinada categoria do Moodle, utilizando o símbolo asterisco (`*`).

- Considere o arquivo de configuração abaixo. Ele é formato por duas categorias, `Matemática` e `Geral`. A categoria `Matemática` é composta pelos cursos da categoria de ID 1 do Moodle que terminam com "mat", por exemplo, `1ef-mat`, `2ef-mat`, etc. Já a categoria `Geral` inclui todos os cursos da categoria de ID 1 do Moodle.

```
Matemática:
1: %mat

Geral:
1: *
```

## 5. Conclusão
- No [próximo tutorial](config_file_usage.md), será apresentado um exemplo de utilização do arquivo de configuração em uma instalação do Moodle, possibilitando a visualização dos resultados dessa configuração.