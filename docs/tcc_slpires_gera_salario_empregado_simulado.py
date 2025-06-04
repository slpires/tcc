
import random

# Lista de empregados oficiais do projeto (matricula, cargo)
empregados = [
    ('916800101', 'Chief Executive Officer (CEO)'),
    ('919020711', 'Chief Operating Officer (COO)'),
    ('919010711', 'Controller'),
    ('918720805', 'Analista Financeiro Sênior'),
    ('918120101', 'Analista Fiscal'),
    ('918650505', 'Analista de Compras'),
    ('919050215', 'Analista Jurídico'),
    ('918300621', 'Assistente Administrativo'),
    ('918650101', 'Recepcionista (Staff)'),
    ('918960414', 'Auxiliar/Serviços Gerais (PCD)'),
    ('919140314', 'Gerente de RH'),
    ('918730720', 'Analista de DP'),
    ('917920627', 'Analista de Desenvolvimento'),
    ('919140315', 'Analista de Benefícios'),
    ('917600211', 'Coordenador(a) de TI'),
    ('918390621', 'Arquiteto de Sistemas'),
    ('919130113', 'Engenheiro(a) de Dados'),
    ('918870305', 'Desenvolvedor(a) Sênior'),
    ('919790727', 'Desenvolvedor(a) Sênior'),
    ('918510728', 'Analista de Segurança'),
    ('919320215', 'Analista de Infraestrutura'),
    ('918650102', 'DevOps'),
    ('917070101', 'Desenvolvedor(a) Júnior'),
    ('918471017', 'Desenvolvedor(a) Júnior'),
    ('918810513', 'Analista de Suporte'),
    ('919340825', 'Analista de Suporte'),
    ('916550101', 'Gerente de Operações'),
    ('918210830', 'Analista de Contratos'),
    ('917510101', 'Analista de Serviços'),
    ('918490819', 'Analista de Projetos'),
    ('919341130', 'Supervisor(a) de Atendimento'),
    ('918531009', 'Supervisor(a) de Atendimento'),
    ('919221026', 'Técnico(a) de Campo'),
    ('918650103', 'Técnico(a) de Campo'),
    ('919420410', 'Assistente de Operações'),
    ('919100609', 'Assistente de Operações'),
    ('918380113', 'Gerente de Inovação'),
    ('918940802', 'Cientista de Dados'),
    ('917000101', 'Analista de Transformação'),
    ('919310618', 'Analista de Projetos')
]

faixas_salariais = {
    "Chief Executive Officer (CEO)": (27000, 40000),
    "Chief Operating Officer (COO)": (22000, 35000),
    "Controller": (15000, 20000),
    "Gerente de RH": (13000, 18000),
    "Gerente de Operações": (13000, 18000),
    "Gerente de Inovação": (13000, 18000),
    "Coordenador(a) de TI": (10000, 15000),
    "Arquiteto de Sistemas": (12000, 18000),
    "Engenheiro(a) de Dados": (14000, 24000),
    "Cientista de Dados": (14000, 24000),
    "DevOps": (10000, 15000),
    "Analista Financeiro Sênior": (8000, 12000),
    "Analista Fiscal": (8000, 12000),
    "Analista de Compras": (8000, 12000),
    "Analista Jurídico": (8000, 12000),
    "Analista de Projetos": (8000, 12000),
    "Desenvolvedor(a) Sênior": (8000, 12000),
    "Analista de Segurança": (8000, 12000),
    "Analista de Infraestrutura": (6000, 9000),
    "Analista de DP": (6000, 8000),
    "Analista de Desenvolvimento": (6000, 8000),
    "Analista de Benefícios": (6000, 8000),
    "Analista de Contratos": (6000, 8000),
    "Analista de Serviços": (6000, 8000),
    "Analista de Transformação": (6000, 8000),
    "Analista de Suporte": (5000, 7000),
    "Supervisor(a) de Atendimento": (4000, 6000),
    "Desenvolvedor(a) Júnior": (4000, 6000),
    "Assistente Administrativo": (3000, 4500),
    "Recepcionista (Staff)": (3000, 4500),
    "Assistente de Operações": (3000, 4500),
    "Técnico(a) de Campo": (2500, 3500),
    "Auxiliar/Serviços Gerais (PCD)": (2000, 2500)
}

reajustes = {
    2019: 8.75,
    2020: 9.31,
    2021: 9.52,
    2022: 15.06,
    2023: 10.79,
    2024: 9.62,
    2025: 9.83
}

anos = list(range(2018, 2026))  # 2018 a 2025

from collections import defaultdict

cargo_to_empregados = defaultdict(list)
for matricula, cargo in empregados:
    cargo_to_empregados[cargo].append(matricula)

salarios_iniciais = {}

for cargo, matriculas in cargo_to_empregados.items():
    matriculas_ordenadas = sorted(matriculas)
    n = len(matriculas)
    t1 = n // 3
    t2 = (2 * n) // 3
    piso, teto = faixas_salariais[cargo]
    for i, matricula in enumerate(matriculas_ordenadas):
        if i < t1:
            faixa = (int(teto * 0.90), teto)
        elif i < t2:
            faixa = (int(piso + (teto - piso) * 0.45), int(piso + (teto - piso) * 0.75))
        else:
            faixa = (piso, int(piso + (teto - piso) * 0.40))
        salario = random.randint(faixa[0], faixa[1])
        salarios_iniciais[matricula] = salario

sql = []
sql.append("USE tcc_slpires;\n\n")

for matricula, cargo in empregados:
    salario = salarios_iniciais[matricula]
    linha = f"('{matricula}', 2018, {salario:.2f}, '2018-01-01', 0, 'Salário inicial conforme faixa e tempo de experiência')"
    inserts = [linha]
    salario_ano = salario
    for ano in range(2019, 2026):
        perc = reajustes[ano]
        novo_salario = round(salario_ano * (1 + perc / 100), 2)
        linha = (
            f"('{matricula}', {ano}, {novo_salario:.2f}, '{ano}-01-01', {perc}, "
            f"'Reajuste anual de salário conforme política da empresa')"
        )
        inserts.append(linha)
        salario_ano = novo_salario
    comando = "INSERT INTO salario_empregado (matricula, ano_referencia, valor_salario, data_reajuste, percentual_reajuste, observacao) VALUES\n"
    comando += ",\n".join(inserts) + ";\n"
    sql.append(comando)

with open("salario_empregado_simulado.sql", "w", encoding="utf-8") as f:
    f.writelines(sql)
print("Script SQL gerado e salvo em 'salario_empregado_simulado.sql'.")
