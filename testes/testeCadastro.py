from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.webdriver.support.ui import Select
import time

driver = webdriver.Chrome()

driver.get("http://localhost/Marcus/Exercicios/banco/loja.php")

time.sleep(2)

link_adicionar = driver.find_element(By.ID, "adicionar")

link_adicionar.click()

time.sleep(2)

campo_nome = driver.find_element(By.NAME, "nome")
campo_nome.send_keys("Feijão")

campo_valor = driver.find_element(By.NAME, "valor")
campo_valor.send_keys("30.00")

campo_quantidade = driver.find_element(By.NAME, "quantidade")
campo_quantidade.send_keys("300")

campo_validade = driver.find_element(By.NAME, "validade")
campo_validade.send_keys("25082026")

time.sleep(2)

botao_cadastrar = driver.find_element(By.CSS_SELECTOR, "button[type='submit']")
botao_cadastrar.click()
time.sleep(3)

driver.quit()