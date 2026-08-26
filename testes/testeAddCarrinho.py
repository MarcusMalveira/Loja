from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.webdriver.support.ui import Select
import time

driver = webdriver.Chrome()
driver.get("[coloque o caminho do arquivo aqui]")

# Seleciona o elemento select pelo ID
select_element = driver.find_element(By.ID, "produto")

#transforma em um select controlavel
select_produto = Select(select_element)

#seleciona o produto "Chocolate" pelo texto visível
select_produto.select_by_visible_text("Chocolate")

time.sleep(2)

#acha o campo quantidade
campo_quantidade = driver.find_element(By.ID, "quantidade")

#digita a quantidade desejada
campo_quantidade.send_keys("2")

time.sleep(2)

#acha o botão de adicionar ao carrinho
botao_carrinho = driver.find_element(By.CSS_SELECTOR, "button[type='submit']")

botao_carrinho.click()
time.sleep(3)

link_voltar = driver.find_element(By.LINK_TEXT, "Continuar comprando")
link_voltar.click()

# Seleciona o elemento select pelo ID
select_element = driver.find_element(By.ID, "produto")

#transforma em um select controlavel
select_produto = Select(select_element)

#seleciona o produto "Chocolate" pelo texto visível
select_produto.select_by_visible_text("Carne")

time.sleep(2)

#acha o campo quantidade
campo_quantidade = driver.find_element(By.ID, "quantidade")

#digita a quantidade desejada
campo_quantidade.send_keys("2")


botao_carrinho = driver.find_element(By.CSS_SELECTOR, "button[type='submit']")
botao_carrinho.click()

time.sleep(3)

link_voltar = driver.find_element(By.LINK_TEXT, "Continuar comprando")
link_voltar.click()

time.sleep(2)

# Seleciona o elemento select pelo ID
select_element = driver.find_element(By.ID, "produto")

#transforma em um select controlavel
select_produto = Select(select_element)

#seleciona o produto "Chocolate" pelo texto visível
select_produto.select_by_visible_text("Carne")

time.sleep(2)

#acha o campo quantidade
campo_quantidade = driver.find_element(By.ID, "quantidade")

#digita a quantidade desejada
campo_quantidade.send_keys("2")

botao_carrinho = driver.find_element(By.CSS_SELECTOR, "button[type='submit']")
botao_carrinho.click()

time.sleep(3)

driver.quit()
