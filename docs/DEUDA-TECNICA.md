# Deuda técnica — Laboratorio de pedidos

**Materia:** Metodología de Sistemas II  
**Carrera:** Tecnicatura Universitaria en Programación — UTN FRRe, sede Formosa  
**Proyecto:** laboratorio-pedidos  
**Modalidad:** Grupal — 2 integrantes

## 1. Objetivo

El objetivo de este trabajo fue identificar problemas de diseño y de proceso en el sistema de pedidos de un laboratorio, aplicar patrones de diseño para mejorar su estructura y utilizar un flujo de trabajo colaborativo mediante Git y GitHub.

Buscamos facilitar el mantenimiento del código, reducir la repetición de lógica y permitir que el sistema incorpore nuevas funcionalidades con menos modificaciones.

## 2. Problemas identificados

Durante el análisis del proyecto encontramos seis problemas de deuda técnica: cuatro relacionados con el diseño del código y dos con el proceso de desarrollo e integración.

| Síntoma observado | Evidencia (archivo:línea) | Tipo de deuda | Patrón aplicado o solución | Consecuencia asumida |
|---|---|---|---|---|
| El cálculo de descuentos estaba concentrado en un `switch`, por lo que agregar un nuevo tipo de paciente obligaba a modificarlo. | `src/Pricing/PriceCalculator.php:30` (versión original) | Diseño — OCP | Strategy | Se agregaron una interfaz y clases independientes para cada algoritmo de cálculo. |
| El envío de notificaciones utilizaba condiciones `if` para elegir entre correo y SMS. | `src/Notifications/NotificationSender.php:34-42` (versión original, numeración por confirmar) | Diseño — OCP | Factory | Se agregó una fábrica y una interfaz común, aumentando la cantidad de clases. |
| Se había agregado el método `send()` a una clase de terceros y se duplicó su comportamiento en otra clase. | `src/Legacy/LegacyNotifier.php:38-51` (versión original, numeración por confirmar) | Diseño — DRY | Adapter | Se agregó una clase intermedia para utilizar el sistema heredado sin modificar su método original. |
| El método `generate()` recibía tres parámetros booleanos para incorporar firma digital, PDF y marca de agua. | `src/Reports/ReportGenerator.php:27-32` (versión original) | Diseño — OCP | Decorator | Se agregaron clases que permiten combinar funcionalidades, con mayor cantidad de objetos. |
| Las dependencias se cargaban manualmente, provocando errores cuando faltaban clases después de integrar los cambios. | `public/index.php:23-35`, bloque `require_once` | Proceso — Integración | Corrección parcial de dependencias | Se corrigieron cargas faltantes, pero todavía es necesario mantener manualmente la lista. |
| La configuración de base de datos estaba escrita directamente en el código fuente. | `public/index.php:51-54`, constantes `DB_HOST`, `DB_NAME`, `DB_USER` y `DB_PASS` | Proceso — Configuración y seguridad | Pendiente: separar la configuración y excluir las credenciales del repositorio | Será necesario administrar un archivo de configuración local para cada entorno. |

## 3. Patrones de diseño aplicados

### 3.1. Strategy — Cálculo de precios

En `PriceCalculator.php` encontramos un `switch` que contenía los algoritmos de cálculo según el tipo de paciente.

Aplicamos Strategy mediante la interfaz `PricingStrategy` y clases independientes para pacientes particulares, con obra social, jubilados y prepagos.

**Ventaja:** permite agregar nuevos algoritmos de cálculo sin modificar `PriceCalculator`.

**Desventaja:** aumenta la cantidad de clases y archivos que debemos mantener.

### 3.2. Factory — Notificaciones

En `NotificationSender.php`, el código utilizaba condiciones para decidir qué notificación debía crear.

Aplicamos Factory para centralizar la creación de notificaciones mediante `NotificationFactory`, utilizando una interfaz común.

**Ventaja:** el emisor deja de necesitar condiciones para construir cada tipo de notificación.

**Desventaja:** la fábrica concentra la selección de implementaciones y debe actualizarse cuando se agregan tipos nuevos.

### 3.3. Adapter — Sistema heredado

En `LegacyNotifier.php` encontramos un método agregado a una clase de un proveedor externo y otra clase que duplicaba su comportamiento.

Aplicamos Adapter para utilizar `sendMessage()` mediante `LegacyNotifierAdapter`, que expone el método `send()` esperado por nuestro sistema.

**Ventaja:** permite utilizar la clase heredada sin modificar su método original.

**Desventaja:** agrega una clase intermedia y otra dependencia que debemos mantener.

### 3.4. Decorator — Generación de reportes

En `ReportGenerator.php` encontramos parámetros booleanos que indicaban si se debía incorporar firma digital, PDF o marca de agua.

Aplicamos Decorator utilizando una interfaz `Report`, un reporte básico y decoradores para incorporar cada funcionalidad.

**Ventaja:** permite combinar las opciones sin concentrarlas en un único método con varias condiciones.

**Desventaja:** aumenta la cantidad de objetos y el orden de aplicación de los decoradores puede modificar el resultado.

## 4. Pruebas realizadas

### 4.1. Prueba de Strategy

Ejecutamos pruebas independientes con un monto inicial de $10.000.

| Tipo de paciente | Monto inicial | Resultado obtenido |
|---|---:|---:|
| Particular | $10.000 | $10.000 |
| Obra social | $10.000 | $7.000 |
| Jubilado | $10.000 | $5.000 |
| Prepaga | $10.000 | $8.000 |

Los cuatro casos devolvieron los resultados esperados.

### 4.2. Pruebas del sistema

Durante la integración encontramos errores relacionados con dependencias de notificaciones que no estaban cargadas en `public/index.php`.

Después de corregirlas, comprobamos estas acciones:

- **Crear pedido:** se mostró el pedido de Juan Pérez con un total de $10.500.
- **Listar pedidos:** aparecieron los pedidos de Juan Pérez y Ana Gómez.
- **Generar reporte:** se mostró el texto del reporte con las opciones de firma digital, PDF y marca de agua.

Estas pruebas comprobaron el funcionamiento básico de las tres acciones, pero no representan una verificación completa de todas las funcionalidades.

## 5. Trabajo con Git y GitHub

Para organizar el desarrollo utilizamos un repositorio compartido en GitHub.

Los dos integrantes trabajamos con ramas independientes para cada patrón, realizando commits propios y creando Pull Requests para incorporar los cambios a `main`.

Cada Pull Request fue revisado por el otro integrante antes de fusionarse.

También se creó la rama `fix/carga-notificaciones` para corregir los errores encontrados durante la integración.

Este procedimiento permitió conservar el historial de modificaciones, separar las tareas y revisar el trabajo de cada integrante.

## 6. Problemas pendientes

Aunque aplicamos los cuatro patrones solicitados, identificamos mejoras que todavía deben realizarse:

- **Cálculo de precios:** existen reglas de descuentos repetidas en otros archivos, como `Order.php` y `OrderController.php`.
- **Dependencias:** la aplicación todavía utiliza instrucciones `require_once` para cargar clases manualmente.
- **Configuración y seguridad:** se debe trasladar la configuración de base de datos a `config/database.php`, excluirla mediante `.gitignore` y proporcionar `config/database.example.php` con valores vacíos.
- **Compatibilidad:** falta verificar que `calcularConIva()` mantenga el comportamiento original para todos los tipos de paciente.
- **Notificaciones:** falta comprobar la incorporación de WhatsApp solicitada en el ejercicio de Factory y evitar posibles declaraciones duplicadas de la interfaz `Notification`.
- **Pruebas:** falta ampliar las pruebas para comprobar la integración de todos los patrones, además de las acciones básicas.

## 7. Conclusión

El trabajo permitió identificar problemas concretos del proyecto original y aplicar patrones de diseño para mejorar la organización del código.

Con Strategy, Factory, Adapter y Decorator separamos responsabilidades que antes estaban concentradas en métodos con condiciones, duplicaciones o dependencias directas.

También comprendimos la importancia de utilizar ramas, commits descriptivos y Pull Requests con revisiones cruzadas.

Si bien los patrones incorporan nuevas clases y cierta complejidad, ofrecen una estructura más organizada para futuras modificaciones. El análisis también permitió identificar problemas pendientes que debemos seguir corrigiendo.