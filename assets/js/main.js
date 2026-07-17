if (localStorage.getItem('nightMode') === 'on') { // Just a way to save if its ON night mode or OFF using browser local storage.
    document.body.classList.add('night-mode');
}

document.getElementById('nightMode').addEventListener('click', function() {
    document.body.classList.toggle('night-mode');

    if (document.body.classList.contains('night-mode')) {
        localStorage.setItem('nightMode', 'on');
    } else {
        localStorage.setItem('nightMode', 'off');
    }
});

var loginForm = document.querySelector('#loginForm');
if (loginForm) {
    loginForm.addEventListener('submit', function(e) {
        var u = document.querySelector('input[name="username"]').value.trim();
        var p = document.querySelector('input[name="password"]').value.trim();
        if (u === '' || p === '') {
            e.preventDefault();
            alert('يرجى تعبئة جميع الحقول');
            document.querySelector('.error').textContent = '\u00A0';
        }
    });
}

document.querySelectorAll('.delete-link').forEach(function(link) {
    link.addEventListener('click', function(e) {
        if (!confirm('هل تريد حذف هذا السجل؟')) {
            e.preventDefault();
        }
    });
});

var searchInput = document.getElementById('searchInput');
var categoryFilter = document.getElementById('categoryFilter');

if (searchInput && categoryFilter) {
    var items = document.querySelectorAll('.gallery-item');
    document.getElementById('resultCount').textContent = 'عدد النتائج: ' + items.length;

    function filterGallery() {
        var search = searchInput.value.trim();
        var category = categoryFilter.value;
        var count = 0;

        for (var i = 0; i < items.length; i++) {
            var name = items[i].querySelector('h3').textContent;
            var itemCategory = items[i].querySelector('.gallery-category').textContent;
            var matchesSearch = name.includes(search) || search === '';
            var matchesCategory = category === 'الكل' || itemCategory === category;

            if (matchesSearch && matchesCategory) {
                items[i].style.display = '';
                count++;
            } else {
                items[i].style.display = 'none';
            }
        }

        document.getElementById('resultCount').textContent = 'عدد النتائج: ' + count;
    }

    searchInput.addEventListener('input', filterGallery);
    categoryFilter.addEventListener('change', filterGallery);
}

var addForm = document.getElementById('addForm');
if (addForm) {
    addForm.addEventListener('submit', function(e) {
        var name = document.querySelector('input[name="name"]').value.trim();
        var category = document.querySelector('select[name="category"]').value;
        var description = document.querySelector('textarea[name="description"]').value.trim();
        var features = document.querySelector('input[name="features"]').value.trim();
        var short_desc = document.querySelector('input[name="short_description"]').value.trim();
        var landmarks = document.querySelector('input[name="landmarks"]').value.trim();
        var mainImg = document.querySelector('input[name="main_image"]').value;
        var gallery1 = document.querySelector('input[name="gallery1"]').value;

        if (name === '' || category === '' || description === '' || features === '' || short_desc === '' || landmarks === '' || mainImg === '' || gallery1 === '') {
            e.preventDefault();
            alert('يرجى تعبئة جميع الحقول المطلوبة');
        }
    });
}

// Update page validation
var updateForm = document.getElementById('updateForm');
if (updateForm) {
    updateForm.addEventListener('submit', function(e) {
        var name = document.querySelector('input[name="name"]').value.trim();
        var category = document.querySelector('select[name="category"]').value;
        var description = document.querySelector('textarea[name="description"]').value.trim();
        var features = document.querySelector('input[name="features"]').value.trim();
        var short_desc = document.querySelector('input[name="short_description"]').value.trim();
        var landmarks = document.querySelector('input[name="landmarks"]').value.trim();

        if (name === '' || category === '' || description === '' || features === '' || short_desc === '' || landmarks === '') {
            e.preventDefault();
            alert('يرجى تعبئة جميع الحقول المطلوبة');
        }
    });
}

