const apiUrl = 'http://localhost/poetry-platform/api/api.php';

document.getElementById('signup-button').addEventListener('click', function() {
    const signupForm = `
        <form id="signup-form">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Sign Up</button>
        </form>
    `;
    document.getElementById('page-content').innerHTML = signupForm;

    document.getElementById('signup-form').addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        fetch(`${apiUrl}?type=signup`, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Sign up successful!');
                loadPage('home');
            } else {
                alert('Error: ' + data.error);
            }
        });
    });
});

function loadPage(page) {
    let content = '';
    if (page === 'home') {
        content = `
            <h2>Home</h2>
            <div id="book" class="animate">
                <p>A short poem:</p>
                <p>Roses are red, violets are blue, sugar is sweet, and so are you!</p>
            </div>
        `;
    } else if (page === 'publication') {
        content = `<h2>Publication</h2>
            <ul id="poems-list"></ul>
            <form id="poem-form">
                <input type="text" name="title" placeholder="Poem Title" required>
                <textarea name="content" placeholder="Write your poem here..." required></textarea>
                <input type="text" name="author" placeholder="Author Name" required>
                <button type="submit">Publish Poem</button>
            </form>`;
        loadPoems();
    } else if (page === 'about') {
        content = `<h2>About Us</h2>
            <p>This platform allows you to read, publish, and enjoy poetry.</p>`;
    }
    document.getElementById('page-content').innerHTML = content;

    if (page === 'publication') {
        document.getElementById('poem-form').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            fetch(`${apiUrl}?type=poem`, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Poem published!');
                    loadPoems();
                    this.reset();
                } else {
                    alert('Error: ' + data.error);
                }
            });
        });
    }
}

function loadPoems() {
    fetch(`${apiUrl}?type=poems`)
        .then(response => response.json())
        .then(data => {
            const poemsList = document.getElementById('poems-list');
            poemsList.innerHTML = '';
            data.forEach(poem => {
                const li = document.createElement('li');
                li.textContent = `${poem.title} by ${poem.author}: ${poem.content}`;
                poemsList.appendChild(li);
            });
        });
}

function searchPoems() {
    const query = document.getElementById('search').value.toLowerCase();
    const poemsList = document.getElementById('poems-list');
    const poems = [...poemsList.children];

    poems.forEach(poem => {
        const title = poem.textContent.toLowerCase();
        poem.style.display = title.includes(query) ? '' : 'none';
    });
}

// Toggle dashboard visibility
document.getElementById('toggle-dashboard').addEventListener('click', function() {
    const nav = document.getElementById('nav');
    nav.style.display = nav.style.display === 'none' || nav.style.display === '' ? 'block' : 'none';
});

// Load the home page by default
loadPage('home');