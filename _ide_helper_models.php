<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * @property int $id
 * @property int $user_id
 * @property string $nama_organisasi
 * @property string|null $jenis_entitas
 * @property string|null $skala_event
 * @property string|null $alamat_organisasi
 * @property string|null $website
 * @property string|null $npwp
 * @property string|null $dokumen_legalitas
 * @property string|null $bank
 * @property string|null $nomor_rekening
 * @property string|null $nama_rekening
 * @property string|null $no_hp_bisnis
 * @property string $status
 * @property string|null $catatan_admin
 * @property int|null $reviewed_by
 * @property \Illuminate\Support\Carbon|null $reviewed_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read string|null $dokumen_url
 * @property-read \App\Models\User|null $reviewer
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EoApplication newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EoApplication newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EoApplication query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EoApplication whereAlamatOrganisasi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EoApplication whereBank($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EoApplication whereCatatanAdmin($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EoApplication whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EoApplication whereDokumenLegalitas($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EoApplication whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EoApplication whereJenisEntitas($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EoApplication whereNamaOrganisasi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EoApplication whereNamaRekening($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EoApplication whereNoHpBisnis($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EoApplication whereNomorRekening($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EoApplication whereNpwp($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EoApplication whereReviewedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EoApplication whereReviewedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EoApplication whereSkalaEvent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EoApplication whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EoApplication whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EoApplication whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EoApplication whereWebsite($value)
 */
	class EoApplication extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $pengelola_id
 * @property string $judul
 * @property string $kategori
 * @property string $lokasi_kota
 * @property string $venue
 * @property \Illuminate\Support\Carbon $tanggal_waktu
 * @property string|null $deskripsi
 * @property string|null $gambar_cover
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $approved_by
 * @property string|null $approved_at
 * @property string|null $catatan_admin
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\EventFaq> $faqs
 * @property-read int|null $faqs_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\EventGallery> $galleries
 * @property-read int|null $galleries_count
 * @property-read string $cover_url
 * @property-read mixed $harga_termurah
 * @property-read int $jumlah_review
 * @property-read float $rating_rata_rata
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\EventLineup> $lineups
 * @property-read int|null $lineups_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Order> $orders
 * @property-read int|null $orders_count
 * @property-read \App\Models\User $pengelola
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\EventReview> $reviews
 * @property-read int|null $reviews_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\TicketCategory> $ticketCategories
 * @property-read int|null $ticket_categories_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Wishlist> $wishlists
 * @property-read int|null $wishlists_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event published()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event upcoming()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereApprovedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereApprovedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereCatatanAdmin($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereDeskripsi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereGambarCover($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereJudul($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereKategori($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereLokasiKota($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event wherePengelolaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereTanggalWaktu($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event whereVenue($value)
 */
	class Event extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $event_id
 * @property string $pertanyaan
 * @property string $jawaban
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Event $event
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventFaq newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventFaq newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventFaq query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventFaq whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventFaq whereEventId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventFaq whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventFaq whereJawaban($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventFaq wherePertanyaan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventFaq whereUpdatedAt($value)
 */
	class EventFaq extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $event_id
 * @property string $path
 * @property int $urutan
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Event $event
 * @property-read string $url
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventGallery newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventGallery newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventGallery query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventGallery whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventGallery whereEventId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventGallery whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventGallery wherePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventGallery whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventGallery whereUrutan($value)
 */
	class EventGallery extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $event_id
 * @property string $nama
 * @property int $is_headliner
 * @property string|null $foto
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Event $event
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventLineup newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventLineup newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventLineup query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventLineup whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventLineup whereEventId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventLineup whereFoto($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventLineup whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventLineup whereIsHeadliner($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventLineup whereNama($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventLineup whereUpdatedAt($value)
 */
	class EventLineup extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $event_id
 * @property int $user_id
 * @property int $order_id
 * @property int $rating
 * @property string|null $ulasan
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Event $event
 * @property-read \App\Models\Order $order
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventReview newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventReview newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventReview query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventReview whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventReview whereEventId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventReview whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventReview whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventReview whereRating($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventReview whereUlasan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventReview whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventReview whereUserId($value)
 */
	class EventReview extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int|null $event_id
 * @property string|null $judul
 * @property string|null $gambar
 * @property string|null $url_tujuan
 * @property int $urutan
 * @property bool $aktif
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Event|null $event
 * @property-read string $image_url
 * @property-read string $link
 * @property-read string $title
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HeroSlider newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HeroSlider newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HeroSlider query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HeroSlider whereAktif($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HeroSlider whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HeroSlider whereEventId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HeroSlider whereGambar($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HeroSlider whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HeroSlider whereJudul($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HeroSlider whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HeroSlider whereUrlTujuan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HeroSlider whereUrutan($value)
 */
	class HeroSlider extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $order_code
 * @property int $user_id
 * @property int $event_id
 * @property numeric $total_harga
 * @property numeric $subtotal
 * @property numeric $biaya_layanan
 * @property numeric $pendapatan_eo
 * @property int $total_qty
 * @property string $status
 * @property string|null $metode_bayar
 * @property string|null $ticket_summary
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Event $event
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\OrderItem> $items
 * @property-read int|null $items_count
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereBiayaLayanan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereEventId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereMetodeBayar($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereOrderCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order wherePendapatanEo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereSubtotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereTicketSummary($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereTotalHarga($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereTotalQty($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereUserId($value)
 */
	class Order extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $order_id
 * @property int $ticket_category_id
 * @property int $qty
 * @property numeric $harga_satuan
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read float $subtotal
 * @property-read \App\Models\Order $order
 * @property-read \App\Models\TicketCategory $ticketCategory
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem whereHargaSatuan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem whereQty($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem whereTicketCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem whereUpdatedAt($value)
 */
	class OrderItem extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $event_id
 * @property string $nama_kategori
 * @property numeric $harga
 * @property int $kuota
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Event $event
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\OrderItem> $orderItems
 * @property-read int|null $order_items_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TicketCategory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TicketCategory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TicketCategory query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TicketCategory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TicketCategory whereEventId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TicketCategory whereHarga($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TicketCategory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TicketCategory whereKuota($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TicketCategory whereNamaKategori($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TicketCategory whereUpdatedAt($value)
 */
	class TicketCategory extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $nama_lengkap
 * @property string $email
 * @property string|null $no_hp
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string $role
 * @property string $status_akun
 * @property bool $eo_verified
 * @property string|null $foto_profil
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\EoApplication|null $eoApplication
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Event> $events
 * @property-read int|null $events_count
 * @property-read string $avatar_url
 * @property-read string $nama_panggilan
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Order> $orders
 * @property-read int|null $orders_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Event> $wishlistedEvents
 * @property-read int|null $wishlisted_events_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Wishlist> $wishlists
 * @property-read int|null $wishlists_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEoVerified($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereFotoProfil($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereNamaLengkap($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereNoHp($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereStatusAkun($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 */
	class User extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $user_id
 * @property int $event_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Event $event
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Wishlist newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Wishlist newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Wishlist query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Wishlist whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Wishlist whereEventId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Wishlist whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Wishlist whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Wishlist whereUserId($value)
 */
	class Wishlist extends \Eloquent {}
}

