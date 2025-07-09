---
layout: home
title: Developers
nav_order: 3
has_children: true
---

# 👩‍💻 Developer Overview

Welcome to the Cardikit developer documentation. This guide will help you contribute, run the project locally, and understand the development workflow.

---

## 🔄 Git Flow

Whether you're fixing a bug or adding a feature, follow the standard contribution process:

### 🧩 Working on an Issue

1. **Fork** the repository
2. **Clone** your fork
3. Create a new branch:
    ```bash
    git checkout -b feature/your-feature-name
    ```
4. Code your solution (see below)
5. Open a **Pull Request** to `main`

---

## 🧪 Code → Test → Document

The expected workflow for all contributions:

| **Step** | **Description** |
| --- | --- |
| **Code** | Build your feature. See [Frontend](./frontend) and/or [Backend](./backend) for best practices. |
| **Test** | Write unit tests to validate your work. Ensure your tests and all previous tests pass. |
| **Doc** | Update docs if functionality changes or is added. |

---

## 🛠️ Development Environment

We recommend using **Docker** for a seamless local setup.

**📦 Services**

| **Service** | **Description** | **Port** |
| --- | --- | --- |
| PHP Server | Backend | 8080 |
| React Frontend | Vite development server | 5173 |
| MySQL | Database | 3306 |
| PhpMyAdmin | Web-based MySQL GUI | 3000 |

---

## 🚀 Getting Started

1. Copy `.env.example` → `.env`

```bash
cp .env.example .env
```

2. Start Docker containers

```bash
docker compose up -d --build
```

3. Run migrations

```bash
./cardikit migrate
```

4. Access the app

- Frontend: [http://localhost](http://localhost)
- PhpMyAdmin: [http://localhost:3000](http://localhost:3000)
    - Username: `cardikit`
    - Password: `password`

---

## 📚 Running Docs Locally

You can run the documentation site on your machine using [Jekyll](https://jekyllrb.com/).

### ✅ Prerequisites

- Ruby (with `gem`)
- Bundler installed (`gem install bundler`)

### 🏃 Steps

1. Enter the `docs` directory

```bash
cd docs
```

2. Install dependencies
```bash
bundle install --vendor/bundle
```

3. Start the server
```bash
bundle exec jekyll serve --baseurl ""
```

4. Open [http://localhost:4000](http://localhost:4000)

## 🧱 Docker Compose

```yaml
services:
  php:
    container_name: cardikit_server
    build:
      context: .
      dockerfile: Dockerfile
    ports:
      - '8080:80'
    volumes:
      - .:/var/www/html
    networks:
      - cardikit
    depends_on:
      - db

  web-frontend:
    container_name: cardikit_web_frontend
    build:
      context: ./web-frontend
      dockerfile: react.Dockerfile
    ports:
      - '80:5173'
    volumes:
      - ./web-frontend:/app
      - /app/node_modules
    networks:
      - cardikit

  db:
    container_name: cardikit_db
    image: mysql:latest
    environment:
      MYSQL_ROOT_PASSWORD: ${MYSQL_ROOT_PASSWORD}
      MYSQL_ROOT_HOST: '%'
      MYSQL_DATABASE: ${MYSQL_DATABASE}
      MYSQL_USER: ${MYSQL_USER}
      MYSQL_PASSWORD: ${MYSQL_PASSWORD}
    ports:
      - 3306:3306
    volumes:
      - cardikit-db:/var/lib/mysql
    networks:
      - cardikit
    healthcheck:
      test:
        - CMD
        - mysqladmin
        - ping
        - '-p${MYSQL_ROOT_PASSWORD}'
      retries: 3
      timeout: 5s

  phpmyadmin:
    container_name: cardikit_phpmyadmin
    image: phpmyadmin/phpmyadmin
    ports:
      - 3000:80
    environment:
      PMA_HOST: db
      PMA_PORT: 3306
      PMA_USER: ${MYSQL_USER}
      PMA_PASSWORD: ${MYSQL_PASSWORD}
    networks:
      - cardikit
    depends_on:
      - db

volumes:
  cardikit-db:

networks:
  cardikit:
```

---

## ⚙️ PHP Dependencies

| **Package** | **Version** |
| --- | --- |
| [Composer](https://getcomposer.org/) | ^2.5.1 |
| [PHP](https://www.php.net/) | ^8.2.0 |
| [Pest](https://pestphp.com/) | ^3.8 |

---

## 🎨 React Dependencies

| **Package** | **Version** |
| --- | --- |
| [React](https://reactjs.org/) | ^19.1.0 |
| [Vite](https://vitejs.dev/) | ^7.0.0 |
| [Typescript](https://www.typescriptlang.org/) | ^5.8.3 |
| [Tailwind](https://tailwindcss.com/) | ^4.1.11 |

---

Need help? [Open an Issue](https://github.com/cardikit/cardikit/issues/new)
