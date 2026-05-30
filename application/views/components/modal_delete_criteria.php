<div id="modalDelete" class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm z-[110] hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-[2rem] w-full max-w-sm overflow-hidden shadow-2xl border-2 border-red-100 animate-fade-in">
        <div class="p-8 text-center">
            <div class="w-20 h-20 bg-red-50 text-red-600 rounded-full flex items-center justify-center mx-auto mb-6">
                <span class="material-symbols-outlined text-5xl">warning</span>
            </div>
            <h3 class="text-2xl font-black text-gray-900 mb-2">Hapus Kriteria?</h3>
            <p class="text-gray-500 font-medium mb-8">
                Anda akan menghapus kriteria <span id="deleteTargetName" class="text-red-600 font-bold"></span>
                beserta seluruh sub-kriterianya secara permanen.
            </p>

            <div class="flex gap-3">
                <button type="button" onclick="closeDeleteModal()" class="flex-1 py-4 bg-gray-100 text-gray-600 font-bold rounded-2xl hover:bg-gray-200 transition-all">
                    Batal
                </button>
                <a id="confirmDeleteBtn" href="#" class="flex-1 py-4 bg-red-600 text-white font-bold rounded-2xl hover:bg-red-700 shadow-lg shadow-red-200 transition-all text-center">
                    Ya, Hapus
                </a>
            </div>
        </div>
    </div>
</div>

<script>
    function openDeleteModal(url, name) {
        document.getElementById('deleteTargetName').textContent = name;
        document.getElementById('confirmDeleteBtn').href = url;
        document.getElementById('modalDelete').classList.remove('hidden');
    }

    function closeDeleteModal() {
        document.getElementById('modalDelete').classList.add('hidden');
    }

    // Penanganan penutupan modal via klik area luar (backdrop) yang aman
    document.addEventListener('DOMContentLoaded', function() {
        const modalDelete = document.getElementById('modalDelete');
        if (modalDelete) {
            modalDelete.addEventListener('click', function(e) {
                if (e.target === this) {
                    closeDeleteModal();
                }
            });
        }
    });
</script>

<style>
    @keyframes fade-in {
        from {
            opacity: 0;
            transform: scale(0.9);
        }

        to {
            opacity: 1;
            transform: scale(1);
        }
    }

    .animate-fade-in {
        animation: fade-in 0.2s ease-out forwards;
    }
</style>