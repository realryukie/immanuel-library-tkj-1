<?php
require_once __DIR__ . '/../repositories/book-repository.php';
require_once __DIR__ . '/../repositories/category-repository.php';
require_once __DIR__ . '/../repositories/author-repository.php';

function getBookRequest($request)
{
    return [
        'id' => trim(htmlspecialchars($request['id'])),
        'title' => trim(htmlspecialchars($request['title'])),
        'isbn' => trim(htmlspecialchars($request['isbn'])),
        'year' => trim(htmlspecialchars($request['year'])),
        'stock' => trim(htmlspecialchars($request['stock'])),
        'description' => trim(htmlspecialchars($request['description'])),
        'category_id' => trim(htmlspecialchars($request['category_id'])),
        'author_ids' => $request['author_ids'] ?? [],
    ];
}

function validateBook($request, $ignoreBookId = 0)
{
    $errors = [];

    if ($request['title'] === '') {
        $errors['title'] = 'Judul buku harus diisi';
    } elseif (mb_strlen($request['title']) > 100) {
        $errors['title'] = 'Judul buku tidak boleh melebihi 100 karakter';
    }

    if ($request['isbn'] === '') {
        $errors['isbn'] = 'ISBN wajib diisi';
    } elseif (isIsbnTaken($request['isbn'], $ignoreBookId)) {
        $errors['isbn'] = 'ISBN sudah terdaftar sebelumnya';
    }

    if ($request['year'] === '') {
        $errors['year'] = 'Tahun terbit wajib diisi';
    } elseif (!is_int(intval($request['year']))) {
        $errors['year'] = 'Tahun terbit wajib diisi dengan angka';
    }

    if ($request['stock'] === '') {
        $errors['stock'] = 'Stok wajib diisi';
    } elseif (!is_int(intval($request['stock']))) {
        $errors['stock'] = 'Stok wajib diisi dengan angka';
    }

    if ($request['category_id'] === '') {
        $errors['category_id'] = 'Kategori wajib dipilih';
    } elseif (!isCategoryExist($request['category_id'])) {
        $errors['category_id'] = 'Kategori yang dipilih tidak valid';
    }

    if ($request['description'] === '') {
        $errors['description'] = 'Deskripsi buku wajib diisi';
    } elseif (mb_strlen($request['description']) > 200) {
        $errors['description'] = 'Deskripsi buku tidak boleh melebihi 200 karakter';
    }

    if (count($request['author_ids']) < 1) {
        $errors['author_ids'] = 'Penulis buku wajib dipilih';
    } else {
        $authors = getAuthors();
        $validAuthorIds = array_column($authors, 'id');
        foreach ($request['author_ids'] as $authorId) {
            if (!in_array($authorId, $validAuthorIds)) {
                $errors['author_ids'] = 'Ada penulis buku yang dipilih tidak valid';
                break;
            }
        }
    }

    return $errors;
}

?>