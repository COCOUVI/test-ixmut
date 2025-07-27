<footer class="bg-black text-white py-4 h-24 flex-shrink-0">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <div class="mb-3 md:mb-0">
                    <p>&copy; <span id="current-year"></span> eXacto. Tous droits réservés.</p>
                </div>
                <div class="text-center md:text-right">
                    <p>Développé par <span class="font-semibold">eXacto</span></p>
                    <p>Contact : eXacto@gmail.com</p>
                </div>
            </div>
        </div>
    </footer>

    <script>
        // Mettre à jour l'année dans le footer
        document.getElementById('current-year').textContent = new Date().getFullYear();
    </script>
