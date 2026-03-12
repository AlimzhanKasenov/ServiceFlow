<p align="center">
<img src="https://img.icons8.com/fluency/512/workflow.png" width="200" alt="ServiceFlow Logo">
</p>

<h1 align="center">ServiceFlow</h1>

<p align="center">
Enterprise Workflow Engine for Internal Service Processes
</p>

<p align="center">
<img src="https://img.shields.io/badge/PHP-8.3-blue">
<img src="https://img.shields.io/badge/Laravel-11-red">
<img src="https://img.shields.io/badge/Vue-3-green">
<img src="https://img.shields.io/badge/PostgreSQL-Database-blue">
<img src="https://img.shields.io/badge/Docker-Containerization-blue">
<img src="https://img.shields.io/badge/License-MIT-green">
</p>

---

# About ServiceFlow

**ServiceFlow** — это универсальная enterprise-платформа для управления внутренними сервисными процессами, заявками и согласованиями.

Система реализует гибкий **workflow engine**, который позволяет организациям создавать собственные бизнес-процессы обработки заявок с помощью:

* pipelines (воронки)
* stages (стадии)
* transition rules (правила переходов)
* automation actions (автоматические действия)

ServiceFlow предназначен для автоматизации внутренних бизнес-процессов компаний.

---

# Key Features

ServiceFlow объединяет возможности нескольких типов систем:

* HelpDesk
* ServiceDesk
* BPM (Business Process Management)
* Approval Systems
* Workflow Management

Основные возможности:

* создание заявок
* настраиваемые pipelines
* гибкие стадии обработки
* правила переходов между стадиями
* система согласований
* SLA контроль
* аудит действий
* роли и права доступа
* Kanban представление
* табличное представление
* полнотекстовый поиск
* webhook интеграции
* multi-tenant архитектура

---

# Workflow Example

Пример обработки заявки:

```
New Request
     ↓
Processing
     ↓
Approval
     ↓
Completed
```

---

# Architecture Overview

Система построена по принципам **Domain Driven Design (DDD)**.

Основные доменные сущности:

| Entity       | Description       |
| ------------ | ----------------- |
| Organization | Компания          |
| Pipeline     | Воронка обработки |
| Stage        | Этап обработки    |
| Request      | Заявка            |
| User         | Пользователь      |
| Role         | Роль              |
| Approval     | Согласование      |
| Task         | Внутренняя задача |
| Comment      | Комментарий       |
| AuditLog     | Журнал действий   |

---

# Domain Model

```
Organization
      │
      ▼
Pipeline
      │
      ▼
Stage
      │
      ▼
Request
      │
      ├── Tasks
      ├── Comments
      ├── Approvals
      └── Audit Logs
```

---

# Technology Stack

Backend

* PHP 8.3
* Laravel 11

Frontend

* Vue 3
* Vite

Database

* PostgreSQL

Infrastructure

* Docker
* Redis
* Git

---

# System Modules

### Core

* Organizations
* Users
* Roles
* Permissions

### Workflow

* Pipelines
* Stages
* Stage Transitions
* Workflow Rules

### Requests

* Requests
* Dynamic Fields
* Attachments

### Approvals

* Approval Chains
* Approval Steps

### Tasks

* Internal Tasks
* Assignments

### Audit

* Audit Logs
* Change History

### Integrations

* Webhooks
* REST API

---

# Project Structure

```
app/

Domain/
Services/
Actions/
Policies/

Models/

Http/
Controllers/
Requests/

Jobs/

database/

migrations/
seeders/

resources/

js/
views/

routes/

api.php
```

---

# Multi-Tenant Architecture

ServiceFlow поддерживает работу нескольких организаций.

Каждая организация имеет:

* собственные pipelines
* собственные пользователей
* собственные заявки
* собственные настройки процессов

---

# REST API

ServiceFlow предоставляет REST API.

Примеры endpoints:

```
GET /api/pipelines
GET /api/stages
GET /api/requests
GET /api/users
```

---

# Kanban Board

Заявки отображаются в виде Kanban доски:

```
Stage 1 | Stage 2 | Stage 3 | Stage 4
```

Каждая карточка — это отдельная заявка.

---

# SLA Monitoring

Система отслеживает:

* время реакции
* время выполнения
* просроченные заявки

---

# Installation

Clone repository

```
git clone repository_url
```

Install dependencies

```
composer install
npm install
```

Run migrations

```
php artisan migrate
```

Start server

```
php artisan serve
```

ServiceFlow Proprietary License
Лицензия на программное обеспечение ServiceFlow

Copyright (c) 2026 Alimzhan Kassenov
Все права защищены.

------------------------------------------------------------------
РУССКАЯ ВЕРСИЯ
------------------------------------------------------------------

Настоящее программное обеспечение "ServiceFlow", включая исходный код,
архитектуру, документацию и связанные файлы (далее — "Программное обеспечение"),
является интеллектуальной собственностью автора.

Автор: Alimzhan Kassenov

Любое использование Программного обеспечения без явного письменного
разрешения автора запрещено.

Без письменного разрешения автора запрещается:

- копировать исходный код
- изменять программное обеспечение
- распространять программное обеспечение
- публиковать программное обеспечение
- продавать или использовать программное обеспечение в коммерческих целях
- создавать производные проекты на основе данного программного обеспечения

Использование данного программного обеспечения допускается только
с письменного разрешения автора.

Нарушение данных условий может повлечь за собой юридическую ответственность
в соответствии с применимым законодательством.

------------------------------------------------------------------
ENGLISH VERSION
------------------------------------------------------------------

ServiceFlow and its source code, architecture, documentation and
associated files (the "Software") are the intellectual property of the author.

Author: Alimzhan Kassenov

All rights reserved.

Unauthorized copying, modification, distribution, publication,
sublicensing, selling or commercial use of this Software is strictly
prohibited without prior written permission from the author.

This Software may not be used, reproduced, modified or distributed
in any form without explicit written consent from the author.

Violation of these terms may result in legal liability under applicable law.
