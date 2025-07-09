![Backend Tests](https://github.com/Cardikit/Cardikit/actions/workflows/tests.yml/badge.svg)
![Frontend Tests](https://github.com/Cardikit/Cardikit/actions/workflows/frontend-tests.yml/badge.svg)
[![License: MIT](https://img.shields.io/badge/License-MIT-blue.svg)](LICENSE)

# CardiKit

**The #1 open-source tool for creating digital business cards.**

Easy to install, quick to deploy, and ready for your personal server - even shared hosting. Build, host, and share your cards with full control.

---

## 🪪 About

Cardikit is an open source digital busines card platform built to be easily deployed anywhere, especially servers with limited capabilites like shared hosting. Its goal is to empower idividuals and teams to host professional profiles with full autonomy.

---

## 🚀 Features

- Easy installation via Docker or shared hosting
- Clean MVC backend with PHP
- Modern React frontend with Tailwind
- Built-in CLI for migrations and testing
- Simple and extensible architecture
- Markdown-powered documentation

---

## 📦 Installation (Docker)

1. Clone the respository.

```bash
git clone https://github.com/Cardikit/Cardikit.git
```

2. Enter the repository.

```bash
cd Cardikit
```

3. Copy the `.env.example` file to `.env`.

```bash
cp .env.example .env
```

4. Build the Docker image.

```bash
docker compose up -d --build
```

5. Run migrations.

```bash
./cardikit migrate
```

6. After it's up:

- Frontend: [http://localhost](http://localhost)
- Backend: [http://localhost:8080](http://localhost:8080)
- PHPMyAdmin: [http://localhost:3000](http://localhost:3000)

Still need help?

- [Read the docs](https://cardikit.github.io/Cardikit/)
- [Open an issue](https://github.com/Cardikit/Cardikit/issues)

---

## 🛠 Contributing

We ❤️ contributors!

**🧭 Git Flow**

1. Check [open issues](https://github.com/Cardikit/Cardikit/issues) or create one
2. Fork the repo
3. Create a new branch `fix/your-branch-name` or `feature/your-branch-name`
4. Code, test, and document
5. Make a pull request

---

## 🐛 Issues & Bugs

- Please use GitHub Issues for all bug reports and feature requests
- Include steps to reproduce if possible
- Label appropriately (`bug`, `feature`, `question`, etc.)

---

## 📚 Documentation

Full docs are available at:

👉 [https://cardikit.github.io/Cardikit/](https://cardikit.github.io/Cardikit/)

Want to run them locally?

**Requirements**

- Ruby (with `gem`)
- Bundler installed (`gem install bundler`)

1. Enter `docs` directory.

```bash
cd docs
```

2. Install dependencies.

```bash
bundle install --path vendor/bundle
```

3. Run the server.

```bash
bundle exec jekyll serve --baseurl ""
```

4. Visit [http://localhost:4000](http://localhost:4000)

---

## 👮 Code of Conduct

In order to ensure that the Cardikit community is welcoming to all, please review and abide by the [Code of Conduct](https://github.com/Cardikit/Cardikit/blob/main/CODE_OF_CONDUCT.md).

---

## 🛡 Security Vulnerabilities

Please review our [Security Policy](https://github.com/Cardikit/Cardikit/blob/main/.github/SECURITY.md) for information on how to report security vulnerabilities.

---

## ⚖ License

Cardikit is open-sourced software licensed under the [MIT license](https://github.com/Cardikit/Cardikit/blob/main/LICENSE).
