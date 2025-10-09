const News = document.getElementById('news');
const Rules = document.getElementById('rules');
const Sublink1 = document.getElementById('sublink1');
const Sublink2 = document.getElementById('sublink2');

News.addEventListener('click', function() {
    if (Sublink1.style.display === 'block') {
        Sublink1.style.display = 'none';
    } else {
        Sublink1.style.display = 'block';
    }
});

Rules.addEventListener('click', function() {
    if (Sublink2.style.display === 'block') {
        Sublink2.style.display = 'none';
    } else {
        Sublink2.style.display = 'block';
    }
});
