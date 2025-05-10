@vite('resources/css/app.css')
@vite(['resources/js/app.js'])

<div
    x-data="{
        isDragging: false,
        selectedFile: null,
        errorMessage: null,
        isUploading: false,
        uploadProgress: 0,
        previewContent: null,
        fileInput: null,

        init() {
            this.$nextTick(() => {
                this.fileInput = this.$refs.fileInput;
            });
        },

        handleDrop(event) {
            this.isDragging = false;
            const files = event.dataTransfer.files;
            if (files.length) {
                this.validateAndProcess(files[0]);
            }
        },

        handleFileSelect(event) {
            if (event.target.files.length) {
                this.validateAndProcess(event.target.files[0]);
            }
        },

        validateAndProcess(file) {
            this.errorMessage = null;
            this.previewContent = null;

            const isCSV = file.type === 'text/csv' ||
                          file.name.toLowerCase().endsWith('.csv') ||
                          file.type === 'application/vnd.ms-excel';

            if (!isCSV) {
                this.errorMessage = 'Only CSV files are allowed';
                if (this.fileInput) this.fileInput.value = '';
                return;
            }

            const maxSize = 80 * 1024 * 1024;
            if (file.size > maxSize) {
                this.errorMessage = 'File size must be less than 80MB';
                if (this.fileInput) this.fileInput.value = '';
                return;
            }

            this.selectedFile = file;
        },

        resetSelection() {
            this.selectedFile = null;
            this.errorMessage = null;
            this.previewContent = null;
            if (this.fileInput) this.fileInput.value = '';
        },

        async submitFile() {
            if (!this.selectedFile) return;

            this.isUploading = true;
            this.uploadProgress = 0;

            try {
                const formData = new FormData();
                formData.append('csv', this.selectedFile);

                const response = await axios.post('{{ route('upload') }}', formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    onUploadProgress: (progressEvent) => {
                        this.uploadProgress = Math.round(
                            (progressEvent.loaded * 100) / progressEvent.total
                        );
                    }
                });

                this.$dispatch('csv-uploaded', response.data);
                this.resetSelection();
                this.showSuccess('CSV uploaded and processed successfully!');
            } catch (error) {
                this.errorMessage = error.response?.data?.message || 'Upload failed. Please check your CSV format.';
                console.error('Upload error:', error);
            } finally {
                this.isUploading = false;
            }
        },

        showSuccess(message) {
            const successEvent = new CustomEvent('show-toast', {
                detail: { message, type: 'success' }
            });
            window.dispatchEvent(successEvent);
        },

        formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(1)) + ' ' + sizes[i];
        },

        previewCSV() {
            if (!this.selectedFile) return;

            const reader = new FileReader();
            reader.onload = (e) => {
                const content = e.target.result;
                const lines = content.split(/\r\n|\n/).slice(0, 5);
                this.previewContent = lines.join('\n');
                alert('First 5 lines:\n\n' + this.previewContent);
            };
            reader.onerror = () => {
                this.errorMessage = 'Error reading CSV file';
            };
            reader.readAsText(this.selectedFile);
        }
    }"
    class="fixed inset-0 z-50 bg-white dark:bg-gray-900"
>
    <form @submit.prevent="submitFile()" class="h-full w-full">
        <div
            x-on:drop.prevent="handleDrop($event)"
            x-on:dragover.prevent="isDragging = true"
            x-on:dragleave.prevent="isDragging = false"
            x-on:dragend.prevent="isDragging = false"
            class="absolute inset-0 flex flex-col items-center justify-center w-full h-full transition-colors duration-200"
            :class="{
                'bg-gray-50 dark:bg-gray-800': isDragging && !selectedFile,
                'cursor-pointer': !selectedFile
            }"
        >
            <template x-if="!selectedFile">
                <label class="flex flex-col items-center justify-center w-full h-full p-8 text-center">
                    <div class="max-w-2xl mx-auto">
                        <div class="mx-auto mb-6 p-5 bg-gray-100 dark:bg-gray-700 rounded-full w-20 h-20 flex items-center justify-center transition-all"
                             :class="{'scale-110': isDragging}">
                            <svg class="w-10 h-10 text-green-500 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <h2 class="text-2xl font-bold text-gray-800 dark:text-white mb-3">
                            <span x-show="!isDragging">Upload CSV File</span>
                            <span x-show="isDragging" class="text-green-600 dark:text-green-400">Drop CSV here</span>
                        </h2>
                        <p class="text-lg text-gray-600 dark:text-gray-300 mb-4">
                            <span class="font-semibold text-green-600 dark:text-green-400">Click to browse</span> or drag and drop
                        </p>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-2">
                            Only CSV files accepted (Max 5MB)
                        </p>

                        <p x-show="errorMessage" class="text-sm text-red-500 dark:text-red-400 mb-4" x-text="errorMessage"></p>

                        <button
                            type="button"
                            @click.prevent="$refs.fileInput.click()"
                            class="px-6 py-2.5 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors"
                        >
                            Select CSV
                        </button>

                        <input
                            x-ref="fileInput"
                            type="file"
                            class="hidden"
                            accept=".csv,text/csv,application/vnd.ms-excel"
                            @change="handleFileSelect($event)"
                        />
                    </div>
                </label>
            </template>

            <!-- File Selected -->
            <template x-if="selectedFile">
                <div class="w-full h-full flex flex-col items-center justify-center p-8">
                    <div class="max-w-2xl w-full bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden border border-gray-200 dark:border-gray-700">
                        <div class="h-48 bg-gray-100 dark:bg-gray-700 flex flex-col items-center justify-center p-4">
                            <div class="bg-gray-200 dark:bg-gray-600 rounded-full p-4 mb-3">
                                <svg class="w-12 h-12 text-green-500 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                            <span class="text-gray-700 dark:text-gray-300 font-medium text-center px-4" x-text="selectedFile.name"></span>
                            <span class="text-sm text-gray-500 dark:text-gray-400 mt-1" x-text="formatFileSize(selectedFile.size)"></span>
                            <button
                                type="button"
                                @click="previewCSV()"
                                class="mt-4 px-4 py-1.5 text-sm bg-gray-200 hover:bg-gray-300 text-gray-700 dark:bg-gray-600 dark:hover:bg-gray-500 dark:text-gray-200 rounded-md"
                            >
                                Preview First 5 Rows
                            </button>
                        </div>

                        <div class="p-6">
                            <div class="flex justify-between items-center mb-4">
                                <div>
                                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">CSV File Ready</h3>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Click submit to process file</p>
                                </div>
                                <button
                                    type="button"
                                    @click="resetSelection()"
                                    class="text-gray-400 hover:text-gray-500 dark:hover:text-gray-300"
                                    title="Change CSV"
                                >
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </div>

                            <div x-show="isUploading" class="w-full bg-gray-200 rounded-full h-2.5 dark:bg-gray-700 mb-4">
                                <div
                                    class="bg-green-600 h-2.5 rounded-full"
                                    :style="`width: ${uploadProgress}%`"
                                ></div>
                            </div>

                            <button
                                type="submit"
                                :disabled="isUploading"
                                class="w-full py-3 px-4 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors"
                            >
                                <span x-show="!isUploading">Process CSV</span>
                                <span x-show="isUploading">Uploading... (<span x-text="uploadProgress"></span>%)</span>
                            </button>
                        </div>
                    </div>
                </div>
            </template>
        </div>

        <button
            type="button"
            class="absolute top-6 right-6 p-2 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 rounded-full hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors"
            @click="$dispatch('close-uploader')"
        >
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </form>
</div>

