        </main>

        </div>

        <footer class="text-center py-4">

            &copy;
            <?= date('Y') ?>

            Nelson Geovetty -

            Todos os direitos reservados

        </footer>

        <script
            src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js">
        </script>

        <script>
            setInterval(() => {
                if (window.location.pathname.endsWith('/admin.php')) {
                    window.location.href = window.location.pathname + '?auto_refresh=1';
                }
            }, 30000);
        </script>
        </body>

        </html>