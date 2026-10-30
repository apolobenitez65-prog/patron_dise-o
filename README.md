# Laboratorio de Pedidos — TP Integrador

**Materia:** Metodología de Sistemas II  
**Carrera:** Tecnicatura Universitaria en Programación — UTN FRRe, sede Formosa  
**Modalidad:** Trabajo grupal — 2 integrantes

## 1. Descripción

Este proyecto consiste en un sistema de gestión de pedidos para un laboratorio de análisis clínicos, desarrollado con PHP sin frameworks.

Se utilizó como base el proyecto de práctica proporcionado por la cátedra, que contenía problemas de diseño y deuda técnica.

El objetivo del trabajo fue identificar esos problemas, aplicar patrones de diseño y utilizar un flujo de colaboración mediante Git y GitHub.

## 2. Tecnologías utilizadas

- PHP 8.0 o superior.
- XAMPP con Apache.
- Git y GitHub.
- Visual Studio Code u otro editor de código.

El proyecto no requiere Composer ni Laravel.

La aplicación utiliza almacenamiento simulado en memoria, por lo que no necesita iniciar MySQL para ejecutar las funciones actuales.

## 3. Instalación y ejecución

### Paso 1. Clonar el repositorio

Abrir una terminal dentro de `C:\xampp\htdocs` y ejecutar:

```bash
git clone https://github.com/apolobenitez65-prog/patron_dise-o.git mini-proyecto-laboratorio
```

### Paso 2. Iniciar el servidor

Abrir el panel de XAMPP e iniciar Apache.

No es necesario iniciar MySQL para esta versión del proyecto.

### Paso 3. Abrir la aplicación

Ingresar desde el navegador:

http://localhost/mini-proyecto-laboratorio/public/index.php

### Paso 4. Probar las acciones disponibles

| Acción | URL |
|---|---|
| Crear pedido | `http://localhost/mini-proyecto-laboratorio/public/index.php?accion=crear` |
| Listar pedidos | `http://localhost/mini-proyecto-laboratorio/public/index.php?accion=listar` |
| Mostrar reporte | `http://localhost/mini-proyecto-laboratorio/public/index.php?accion=reporte` |

Los datos utilizados son de demostración. La aplicación no guarda los pedidos en una base de datos permanente.

## 4. Patrones de diseño implementados

Durante el desarrollo se trabajó con cuatro patrones:

| Patrón | Problema identificado | Solución |
|---|---|---|
| Strategy | Cálculo de precios concentrado en un `switch`. | Separación de algoritmos mediante `PricingStrategy` y clases independientes. |
| Factory | Condiciones repetidas para crear notificaciones. | Centralización de la creación de objetos mediante `NotificationFactory`. |
| Adapter | Modificación y duplicación de código heredado. | Adaptación mediante `LegacyNotifierAdapter`. |
| Decorator | Uso de parámetros booleanos para agregar opciones a reportes. | Creación de decoradores para firma digital, PDF y marca de agua. |

Las clases de estos patrones se incorporaron al repositorio. Algunas partes del sistema todavía mantienen la implementación original, por lo que la integración no está completamente finalizada.

## 5. Pruebas

### Prueba de Strategy

Desde la carpeta raíz del proyecto, ejecutar:

```bash
php tests/StrategyTest.php
```

El archivo verifica los cálculos correspondientes a cuatro tipos de pacientes:

| Tipo | Monto de prueba | Resultado esperado |
|---|---:|---:|
| Particular | $10.000 | $10.000 |
| Obra social | $10.000 | $7.000 |
| Jubilado | $10.000 | $5.000 |
| Prepaga | $10.000 | $8.000 |

### Prueba de Adapter

Ejecutar:

```bash
php tests/AdapterTest.php
```

Esta prueba verifica que el adaptador pueda invocar el método original del sistema heredado y obtener el mensaje esperado.

### Pruebas desde el navegador

Verificar las acciones `crear`, `listar` y `reporte` utilizando las direcciones indicadas en la sección 3.

El reporte actual devuelve una representación en texto de las opciones seleccionadas. No genera un documento PDF real.

## 6. Organización del proyecto

| Carpeta o archivo | Función |
|---|---|
| `public/index.php` | Punto de entrada de la aplicación. |
| `src/Controllers/` | Controladores. |
| `src/Models/` | Modelos de datos. |
| `src/Pricing/` | Cálculo de precios y estrategias. |
| `src/Notifications/` | Notificaciones y fábrica de objetos. |
| `src/Legacy/` | Sistema heredado y adaptador. |
| `src/Reports/` | Reportes y decoradores. |
| `src/Services/` | Servicios del sistema. |
| `views/` | Vistas. |
| `tests/` | Pruebas del proyecto. |
| `docs/` | Documentación de deuda técnica. |

## 7. Metodología de trabajo

El desarrollo se realizó mediante ramas independientes y Pull Requests.

Cada integrante trabajó en sus patrones asignados, registró sus modificaciones con commits y participó en revisiones cruzadas antes de integrar los cambios a `main`.

También se realizaron correcciones de integración en una rama independiente.

## 8. Deuda técnica y limitaciones

El análisis completo se encuentra en:

`docs/DEUDA-TECNICA.md`

Entre las mejoras pendientes se identificaron:

- Centralizar todas las reglas de precios que siguen repetidas.
- Reemplazar la carga manual de clases.
- Separar la configuración de base de datos del código versionado.
- Completar la integración de los patrones en los componentes que todavía utilizan código anterior.
- Ampliar las pruebas para comprobar el comportamiento de todos los componentes.

## 9. Objetivo alcanzado

El trabajo permitió practicar la aplicación de patrones de diseño y la colaboración sobre un repositorio compartido.

Se crearon componentes que organizan mejor los cálculos, las notificaciones y los reportes, aunque todavía existen tareas pendientes para completar la refactorización del sistema.