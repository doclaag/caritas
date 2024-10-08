const validateAndUploadFile = async () => {
    if (!selectedFile.value) {
        addNotification('error', 'Por favor, selecciona un archivo primero.');
        return;
    }
    if (!form.value.categoria) {
        addNotification('error', 'Por favor, selecciona una categoría.');
        return;
    }

    try {
        const formData = new FormData();
        formData.append('file', selectedFile.value);
        formData.append('estado', form.value.estado);
        formData.append('publico', form.value.publico);
        formData.append('categoria', form.value.categoria);
        formData.append('tags', JSON.stringify(selectedTags.value)); // Envías los IDs de las etiquetas como un array JSON
        console.log('Datos enviados:', {
            file: selectedFile.value.name,
            estado: form.value.estado,
            publico: form.value.publico,
            categoria: form.value.categoria,
            tags: selectedTags.value
        });

        await uploadFile();
    } catch (error) {
        console.error('Error al validar y subir archivo:', error);
        if (error.response && error.response.status === 409) {
            modalMessage.value = 'El archivo ya existe. ¿Quieres renombrarlo o reemplazarlo?';
            showModal.value = true;
        } else {
            addNotification('error', 'Error al subir el archivo');
        }
    }
};
