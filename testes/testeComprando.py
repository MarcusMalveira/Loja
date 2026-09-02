from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.webdriver.support.ui import Select
import time

driver = webdriver.Chrome()

driver.get("http://localhost/Marcus/Exercicios/banco/loja.php")

# Seleciona Milho
select_element = driver.find_element(By.ID, "produto")
select_produto = Select(select_element)

select_produto.select_by_visible_text("milho")

time.sleep(2)

# Coloca 20 unidades
campo_quantidade = driver.find_element(By.ID, "quantidade")
campo_quantidade.send_keys("20")

time.sleep(2)

# Adiciona ao carrinho
botao_carrinho = driver.find_element(
    By.CSS_SELECTOR,
    "button[type='submit']"
)

botao_carrinho.click()

time.sleep(3)

# Limpa o carrinho
botao_limpar = driver.find_element(
    By.XPATH,
    "//button[text()='Limpar Carrinho']"
)

botao_limpar.click()

time.sleep(3)

# Volta para a loja
link_voltar = driver.find_element(
    By.LINK_TEXT,
    "Voltar para a loja"
)

link_voltar.click()

time.sleep(3)

# Seleciona Milho novamente
select_element = driver.find_element(By.ID, "produto")
select_produto = Select(select_element)

select_produto.select_by_visible_text("milho")

time.sleep(2)

# Adiciona agora apenas 15 unidades
campo_quantidade = driver.find_element(By.ID, "quantidade")
campo_quantidade.send_keys("15")

time.sleep(2)

botao_carrinho = driver.find_element(
    By.CSS_SELECTOR,
    "button[type='submit']"
)

botao_carrinho.click()

time.sleep(3)

# Finaliza a compra
botao_finalizar = driver.find_element(
    By.XPATH,
    "//button[text()='Finalizar Compra']"
)

botao_finalizar.click()

time.sleep(5)

driver.quit()