---
layout: default
title: "Bem-vindo ao Blog Lucre Mais"
---

# Bem-vindo ao Blog **Lucre Mais** 💰

Aqui você aprende como ganhar dinheiro com a internet, renda extra e marketing digital de verdade!

---

## Últimos posts

<ul>
  {% for post in site.posts limit:5 %}
    <li>
      <a href="{{ post.url }}">{{ post.title }}</a> — {{ post.date | date: "%d/%m/%Y" }}
    </li>
  {% endfor %}
</ul>
