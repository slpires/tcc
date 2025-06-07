# ============================================================
# SCRIPT DE GERAÇÃO DA CARGA INICIAL DO MVP - SLPIRES.COM
# Geração automática das tabelas CONTRACHEQUE e RUBRICA_CONTRACHEQUE
# Ano-base: 2018 | Fonte: Lista oficial de empregados, gênero e salário base
# Semente fixa para reprodutibilidade: 42
# ============================================================

import csv
import random
import math

# ------------------------------------------------------------
# CONFIGURAÇÃO INICIAL
# ------------------------------------------------------------
random.seed(42)  # Semente para garantir reprodutibilidade

empregados = [
    ('916550101', 'M', 14324.00), ('916800101', 'F', 30383.00),
    ('917000101', 'F', 6555.00),  ('917070101', 'M', 5007.00),
    ('917510101', 'F', 6186.00),  ('917600211', 'F', 11809.00),
    ('917920627', 'F', 6737.00),  ('918120101', 'F', 9337.00),
    ('918210830', 'F', 6286.00),  ('918300621', 'M', 3493.00),
    ('918380113', 'M', 14682.00), ('918390621', 'M', 14105.00),
    ('918471017', 'F', 4471.00),  ('918490819', 'M', 10110.00),
    ('918510728', 'M', 8425.00),  ('918531009', 'M', 5423.00),
    ('918650101', 'F', 3487.00),  ('918650102', 'F', 11402.00),
    ('918650103', 'F', 3221.00),  ('918650505', 'M', 8791.00),
    ('918720805', 'M', 8812.00),  ('918730720', 'M', 6603.00),
    ('918810513', 'M', 6352.00),  ('918870305', 'M', 10893.00),
    ('918940802', 'F', 15707.00), ('918960414', 'M', 2010.00),
    ('919010711', 'F', 16438.00), ('919020711', 'M', 23673.00),
    ('919050215', 'F', 8496.00),  ('919100609', 'F', 3928.00),
    ('919130113', 'F', 15137.00), ('919140314', 'F', 13501.00),
    ('919140315', 'M', 6345.00),  ('919221026', 'M', 2668.00),
    ('919310618', 'M', 9151.00),  ('919320215', 'M', 7165.00),
    ('919340825', 'F', 5090.00),  ('919341130', 'F', 4245.00),
    ('919420410', 'M', 3257.00),  ('919790727', 'F', 9563.00),
]

# ------------------------------------------------------------
# FUNÇÕES DE APOIO
# ------------------------------------------------------------

def ultimo_dia_pagamento(mes, tipo_folha):
    """
    Retorna a data de pagamento correta conforme tipo de folha e mês.
    """
    if tipo_folha == '13_SALARIO':
        if mes == 11:
            return '2018-11-30'
        elif mes == 12:
            return '2018-12-20'
    if mes == 2:
        return '2018-02-28'
    return f'2018-{mes:02d}-30'

def inss_2018(salario):
    """
    Cálculo oficial do INSS para 2018.
    """
    faixas = [
        (1693.72, 0.08),
        (2822.90, 0.09),
        (5645.80, 0.11),
    ]
    teto = 5645.80
    if salario > teto:
        return teto * 0.11
    for limite, aliq in faixas:
        if salario <= limite:
            return salario * aliq
    return salario * 0.11

def irrf_2018(base):
    """
    Cálculo oficial do IRRF para 2018 (faixas e deduções).
    """
    faixas = [
        (1903.98, 0.00, 0.00),
        (2826.65, 0.075, 142.80),
        (3751.05, 0.15, 354.80),
        (4664.68, 0.225, 636.13),
        (float('inf'), 0.275, 869.36),
    ]
    for limite, aliq, deducao in faixas:
        if base <= limite:
            imposto = max(0, base * aliq - deducao)
            return imposto
    return 0.0

# ------------------------------------------------------------
# SORTEIO DOS GRUPOS DE DESCONTOS (PADRÃO DO PROJETO)
# ------------------------------------------------------------

homens = [(m, g, s) for m, g, s in empregados if g == 'M']
n_pensao = math.ceil(len(homens) * 0.15)
pensao_group = random.sample(homens, n_pensao)
pensao_matriculas = [m for m, g, s in pensao_group]

n_consignado = math.ceil(len(empregados) * 0.40)
consignado_group = random.sample(empregados, n_consignado)
consignado_matriculas = [m for m, g, s in consignado_group]

consignado_percentuais = {m: random.uniform(0.10, 0.30) for m in consignado_matriculas}

# ------------------------------------------------------------
# GERAÇÃO DOS DADOS E LOGS
# ------------------------------------------------------------

sql_contracheque = []
sql_rubrica = []
coparticipacao_log = []

for matricula, genero, salario in empregados:
    for mes in range(1, 13):
        tipo_folha = 'MENSAL'
        data_pagamento = ultimo_dia_pagamento(mes, tipo_folha)

        proventos = salario
        inss = inss_2018(salario)
        prev = salario * 0.10
        med_fixa = salario * 0.05

        perc_copart = random.uniform(0.00, 0.02)
        copart = salario * perc_copart
        coparticipacao_log.append([matricula, data_pagamento, round(perc_copart * 100, 2)])

        pensao = salario * 0.20 if matricula in pensao_matriculas else 0.0
        if matricula in consignado_matriculas:
            perc_consig = consignado_percentuais[matricula]
            valor_consig = salario * perc_consig
        else:
            perc_consig = 0.0
            valor_consig = 0.0

        descontos_brutos = inss + prev + med_fixa + copart + pensao + valor_consig
        base_irrf = proventos - inss - prev - pensao
        irrf = irrf_2018(base_irrf)
        descontos_totais = descontos_brutos + irrf
        liquido = proventos - descontos_totais

        if liquido < salario * 0.30 and valor_consig > 0:
            descontos_totais -= valor_consig
            valor_consig = 0.0
            liquido = proventos - descontos_totais

        sql_contracheque.append(
            f"INSERT INTO CONTRACHEQUE (matricula, data_pagamento, tipo_folha, status_contracheque, data_geracao, observacoes) "
            f"VALUES ('{matricula}', '{data_pagamento}', '{tipo_folha}', 'PAGO', NOW(), 'Carga inicial – geração automatizada para simulação do MVP');"
        )
        sql_rubrica.append(
            f"INSERT INTO RUBRICA_CONTRACHEQUE (matricula, data_pagamento, tipo_folha, cod_rubrica, descricao_rubrica, tipo_rubrica, valor, observacao) "
            f"VALUES ('{matricula}', '{data_pagamento}', '{tipo_folha}', '0001', 'Salário Base', 'provento', {salario:.2f}, 'Salário base do mês.');"
        )
        sql_rubrica.append(
            f"INSERT INTO RUBRICA_CONTRACHEQUE (matricula, data_pagamento, tipo_folha, cod_rubrica, descricao_rubrica, tipo_rubrica, valor, observacao) "
            f"VALUES ('{matricula}', '{data_pagamento}', '{tipo_folha}', '1002', 'INSS', 'desconto', {inss:.2f}, 'Desconto INSS (faixa real 2018).');"
        )
        sql_rubrica.append(
            f"INSERT INTO RUBRICA_CONTRACHEQUE (matricula, data_pagamento, tipo_folha, cod_rubrica, descricao_rubrica, tipo_rubrica, valor, observacao) "
            f"VALUES ('{matricula}', '{data_pagamento}', '{tipo_folha}', '1003', 'Previdência Privada', 'desconto', {prev:.2f}, 'Desconto previdência privada (10%).');"
        )
        sql_rubrica.append(
            f"INSERT INTO RUBRICA_CONTRACHEQUE (matricula, data_pagamento, tipo_folha, cod_rubrica, descricao_rubrica, tipo_rubrica, valor, observacao) "
            f"VALUES ('{matricula}', '{data_pagamento}', '{tipo_folha}', '1004', 'Assistência Médica Fixa', 'desconto', {med_fixa:.2f}, 'Desconto fixo saúde (5%).');"
        )
        sql_rubrica.append(
            f"INSERT INTO RUBRICA_CONTRACHEQUE (matricula, data_pagamento, tipo_folha, cod_rubrica, descricao_rubrica, tipo_rubrica, valor, observacao) "
            f"VALUES ('{matricula}', '{data_pagamento}', '{tipo_folha}', '1005', 'Coparticipação Médica', 'desconto', {copart:.2f}, 'Coparticipação saúde ({perc_copart*100:.2f}%).');"
        )
        if pensao > 0:
            sql_rubrica.append(
                f"INSERT INTO RUBRICA_CONTRACHEQUE (matricula, data_pagamento, tipo_folha, cod_rubrica, descricao_rubrica, tipo_rubrica, valor, observacao) "
                f"VALUES ('{matricula}', '{data_pagamento}', '{tipo_folha}', '1006', 'Pensão Judicial', 'desconto', {pensao:.2f}, 'Desconto judicial (20% grupo sorteado).');"
            )
        if valor_consig > 0:
            sql_rubrica.append(
                f"INSERT INTO RUBRICA_CONTRACHEQUE (matricula, data_pagamento, tipo_folha, cod_rubrica, descricao_rubrica, tipo_rubrica, valor, observacao) "
                f"VALUES ('{matricula}', '{data_pagamento}', '{tipo_folha}', '1008', 'Empréstimo Consignado', 'desconto', {valor_consig:.2f}, 'Consignado ({perc_consig*100:.2f}%), grupo sorteado e respeitado limite 30%.');"
            )
        if irrf > 0:
            sql_rubrica.append(
                f"INSERT INTO RUBRICA_CONTRACHEQUE (matricula, data_pagamento, tipo_folha, cod_rubrica, descricao_rubrica, tipo_rubrica, valor, observacao) "
                f"VALUES ('{matricula}', '{data_pagamento}', '{tipo_folha}', '1001', 'IRRF', 'desconto', {irrf:.2f}, 'Desconto IRRF (faixa real 2018, com dedução).');"
            )
        sql_rubrica.append(
            f"INSERT INTO RUBRICA_CONTRACHEQUE (matricula, data_pagamento, tipo_folha, cod_rubrica, descricao_rubrica, tipo_rubrica, valor, observacao) "
            f"VALUES ('{matricula}', '{data_pagamento}', '{tipo_folha}', '2000', 'Líquido', 'liquido', {liquido:.2f}, 'Valor líquido do mês.');"
        )

    for mes, desc in [(11, 'Adiantamento do 13º Salário'), (12, 'Quitação do 13º Salário')]:
        tipo_folha = '13_SALARIO'
        data_pagamento = ultimo_dia_pagamento(mes, tipo_folha)
        proventos = salario
        inss = inss_2018(salario)
        prev = salario * 0.10
        base_irrf = proventos - inss - prev
        irrf = irrf_2018(base_irrf)
        descontos_brutos = inss + prev
        descontos_totais = descontos_brutos + irrf
        liquido = proventos - descontos_totais
        sql_contracheque.append(
            f"INSERT INTO CONTRACHEQUE (matricula, data_pagamento, tipo_folha, status_contracheque, data_geracao, observacoes) "
            f"VALUES ('{matricula}', '{data_pagamento}', '{tipo_folha}', 'PAGO', NOW(), 'Carga inicial – geração automatizada para simulação do MVP – {desc}');"
        )
        sql_rubrica.append(
            f"INSERT INTO RUBRICA_CONTRACHEQUE (matricula, data_pagamento, tipo_folha, cod_rubrica, descricao_rubrica, tipo_rubrica, valor, observacao) "
            f"VALUES ('{matricula}', '{data_pagamento}', '{tipo_folha}', '0004', '{desc}', 'provento', {salario:.2f}, '{desc} referente ao salário base.');"
        )
        sql_rubrica.append(
            f"INSERT INTO RUBRICA_CONTRACHEQUE (matricula, data_pagamento, tipo_folha, cod_rubrica, descricao_rubrica, tipo_rubrica, valor, observacao) "
            f"VALUES ('{matricula}', '{data_pagamento}', '{tipo_folha}', '1002', 'INSS', 'desconto', {inss:.2f}, 'Desconto INSS (faixa real 2018).');"
        )
        sql_rubrica.append(
            f"INSERT INTO RUBRICA_CONTRACHEQUE (matricula, data_pagamento, tipo_folha, cod_rubrica, descricao_rubrica, tipo_rubrica, valor, observacao) "
            f"VALUES ('{matricula}', '{data_pagamento}', '{tipo_folha}', '1003', 'Previdência Privada', 'desconto', {prev:.2f}, 'Desconto previdência privada (10%).');"
        )
        if irrf > 0:
            sql_rubrica.append(
                f"INSERT INTO RUBRICA_CONTRACHEQUE (matricula, data_pagamento, tipo_folha, cod_rubrica, descricao_rubrica, tipo_rubrica, valor, observacao) "
                f"VALUES ('{matricula}', '{data_pagamento}', '{tipo_folha}', '1001', 'IRRF', 'desconto', {irrf:.2f}, 'Desconto IRRF (faixa real 2018, 13º).');"
            )
        sql_rubrica.append(
            f"INSERT INTO RUBRICA_CONTRACHEQUE (matricula, data_pagamento, tipo_folha, cod_rubrica, descricao_rubrica, tipo_rubrica, valor, observacao) "
            f"VALUES ('{matricula}', '{data_pagamento}', '{tipo_folha}', '2000', 'Líquido', 'liquido', {liquido:.2f}, 'Valor líquido do {desc.lower()}.');"
        )

# ------------------------------------------------------------
# EXPORTAÇÃO DOS ARQUIVOS PARA O PROJETO (PADRÃO SLPIRES.COM)
# ------------------------------------------------------------

with open('contracheque_inserts.sql', 'w', encoding='utf-8') as f:
    for sql in sql_contracheque:
        f.write(sql + '\n')

with open('rubrica_contracheque_inserts.sql', 'w', encoding='utf-8') as f:
    for sql in sql_rubrica:
        f.write(sql + '\n')

with open('coparticipacao_medica_log.csv', 'w', encoding='utf-8', newline='') as f:
    writer = csv.writer(f)
    writer.writerow(['matricula', 'data_pagamento', 'percentual'])
    for row in coparticipacao_log:
        writer.writerow(row)

with open('grupo_pensao_judicial.csv', 'w', newline='', encoding='utf-8') as f:
    writer = csv.writer(f)
    writer.writerow(['matricula', 'genero', 'valor_salario'])
    for row in pensao_group:
        writer.writerow(row)

with open('grupo_consignado.csv', 'w', newline='', encoding='utf-8') as f:
    writer = csv.writer(f)
    writer.writerow(['matricula', 'genero', 'valor_salario'])
    for row in consignado_group:
        writer.writerow(row)

print("================================================================")
print(" CARGA INICIAL DO MVP - SLPIRES.COM GERADA COM SUCESSO")
print(" Os arquivos .sql e .csv foram salvos na mesma pasta deste script.")
print(" Todos os padrões e requisitos do projeto foram atendidos.")
print("================================================================")
