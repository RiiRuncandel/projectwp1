function filterGallery(category) {
  const items = document.querySelectorAll('.gallery-item');
  items.forEach(item => {
    if (category === 'all') {
      item.classList.remove('hidden');
    } else {
      item.classList.toggle('hidden', !item.classList.contains(category));
    }
  });
}
