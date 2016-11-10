<?php

	require 'stem.php';

	class PraprosesLatih
	{
		protected $this;
		private $stopwords2 = array("a", "about", "above", "acara", "across", "ada", "adalah", "adanya", "after", "afterwards", "again", "against", "agar", "akan", 
	        	"akhir", "akhirnya", "akibat", "aku", "all", "almost", "alone", "along", "already", "also", "although", "always", "am", "among", "amongst", 
	        	"amoungst", "amount", "an", "and", "anda", "another", "antara", "any", "anyhow", "anyone", "anything", "anyway", "anywhere", "apa", "apakah", 
	        	"apalagi", "are", "around", "as", "asal", "at", "atas", "atau", "awal", "b", "back", "badan", "bagaimana", "bagi", "bagian", "bahkan", "bahwa", 
	        	"baik", "banyak", "barang", "barat", "baru", "bawah", "be", "beberapa", "became", "because", "become", "becomes", "becoming", "been", "before", 
	        	"beforehand", "begitu", "behind", "being", "belakang", "below", "belum", "benar", "bentuk", "berada", "berarti", "berat", "berbagai", 
	        	"berdasarkan", "berjalan", "berlangsung", "bersama", "bertemu", "besar", "beside", "besides", "between", "beyond", "biasa", "biasanya", "bila", 
	        	"bill", "bisa", "both", "bottom", "bukan", "bulan", "but", "by", "call", "can", "cannot", "cant", "cara", "co", "con", "could", "couldnt", "cry", 
	        	"cukup", "dalam", "dan", "dapat", "dari", "datang", "de", "dekat", "demikian", "dengan", "depan", "describe", "detail", "di", "dia", "diduga", 
	        	"digunakan", "dilakukan", "diri", "dirinya", "ditemukan", "do", "done", "down", "dua", "due", "dulu", "during", "each", "eg", "eight", "either", 
	        	"eleven", "else", "elsewhere", "empat", "empty", "enough", "etc", "even", "ever", "every", "everyone", "everything", "everywhere", "except", 
	        	"few", "fifteen", "fify", "fill", "find", "fire", "first", "five", "for", "former", "formerly", "forty", "found", "four", "from", "front", 
	        	"full", "further", "gedung", "get", "give", "go", "had", "hal", "hampir", "hanya", "hari", "harus", "has", "hasil", "hasnt", "have", "he", 
	        	"hence", "her", "here", "hereafter", "hereby", "herein", "hereupon", "hers", "herself", "hidup", "him", "himself", "hingga", "his", "how", 
	        	"however", "hubungan", "hundred", "ia", "ie", "if", "ikut", "in", "inc", "indeed", "ingin", "ini", "interest", "into", "is", "it", "its", 
	        	"itself", "itu", "jadi", "jalan", "jangan", "jauh", "jelas", "jenis", "jika", "juga", "jumat", "jumlah", "juni", "justru", "juta", "kalau", 
	        	"kali", "kami", "kamis", "karena", "kata", "katanya", "ke", "kebutuhan", "kecil", "kedua", "keep", "kegiatan", "kehidupan", "kejadian", "keluar", 
	        	"kembali", "kemudian", "kemungkinan", "kepada", "keputusan", "kerja", "kesempatan", "keterangan", "ketiga", "ketika", "khusus", "kini", "kita", 
	        	"kondisi", "kurang", "lagi", "lain", "lainnya", "lalu", "lama", "langsung", "lanjut", "last", "latter", "latterly", "least", "lebih", "less", 
	        	"lewat", "lima", "ltd", "luar", "made", "maka", "mampu", "mana", "mantan", "many", "masa", "masalah", "masih", "masing-masing", "masuk", "mau", 
	        	"maupun", "may", "me", "meanwhile", "melakukan", "melalui", "melihat", "memang", "membantu", "membawa", "memberi", "memberikan", "membuat", 
	        	"memiliki", "meminta", "mempunyai", "mencapai", "mencari", "mendapat", "mendapatkan", "menerima", "mengaku", "mengalami", "mengambil", 
	        	"mengatakan", "mengenai", "mengetahui", "menggunakan", "menghadapi", "meningkatkan", "menjadi", "menjalani", "menjelaskan", "menunjukkan", 
	        	"menurut", "menyatakan", "menyebabkan", "menyebutkan", "merasa", "mereka", "merupakan", "meski", "might", "milik", "mill", "mine", "minggu", 
	        	"misalnya", "more", "moreover", "most", "mostly", "move", "much", "mulai", "muncul", "mungkin", "must", "my", "myself", "nama", "name", "namely", 
	        	"namun", "nanti", "neither", "never", "nevertheless", "next", "nine", "no", "nobody", "none", "noone", "nor", "not", "nothing", "now", "nowhere", 
	        	"of", "off", "often", "oleh", "on", "once", "one", "only", "onto", "or", "orang", "other", "others", "otherwise", "our", "ours", "ourselves", 
	        	"out", "over", "own", "pada", "padahal", "pagi", "paling", "panjang", "para", "part", "pasti", "pekan", "penggunaan", "penting", "per", "perhaps", 
	        	"perlu", "pernah", "persen", "pertama", "pihak", "please", "posisi", "program", "proses", "pula", "pun", "punya", "put", "rabu", "rasa", "rather", 
	        	"re", "ribu", "ruang", "saat", "sabtu", "saja", "salah", "sama", "same", "sampai", "sangat", "satu", "saya", "sebab", "sebagai", "sebagian", 
	        	"sebanyak", "sebelum", "sebelumnya", "sebenarnya", "sebesar", "sebuah", "secara", "sedang", "sedangkan", "sedikit", "see", "seem", "seemed", 
	        	"seeming", "seems", "segera", "sehingga", "sejak", "sejumlah", "sekali", "sekarang", "sekitar", "selain", "selalu", "selama", "selasa", "selatan", 
	        	"seluruh", "semakin", "sementara", "sempat", "semua", "sendiri", "senin", "seorang", "seperti", "sering", "serious", "serta", "sesuai", "setelah", 
	        	"setiap", "several", "she", "should", "show", "side", "since", "sincere", "six", "sixty", "so", "some", "somehow", "someone", "something", 
	        	"sometime", "sometimes", "somewhere", "still", "suatu", "such", "sudah", "sumber", "system", "tahu", "tahun", "tak", "take", "tampil", "tanggal", 
	        	"tanpa", "tapi", "telah", "teman", "tempat", "ten", "tengah", "tentang", "tentu", "terakhir", "terhadap", "terjadi", "terkait", "terlalu", 
	        	"terlihat", "termasuk", "ternyata", "tersebut", "terus", "terutama", "tetapi", "than", "that", "the", "their", "them", "themselves", "then", 
	        	"thence", "there", "thereafter", "thereby", "therefore", "therein", "thereupon", "these", "they", "thickv", "thin", "third", "this", "those", 
	        	"though", "three", "through", "throughout", "thru", "thus", "tidak", "tiga", "tinggal", "tinggi", "tingkat", "to", "together", "too", "top", 
	        	"toward", "towards", "twelve", "twenty", "two", "ujar", "umum", "un", "under", "until", "untuk", "up", "upaya", "upon", "us", "usai", "utama", 
	        	"utara", "very", "via", "waktu", "was", "we", "well", "were", "what", "whatever", "when", "whence", "whenever", "where", "whereafter", 
	        	"whereas", "whereby", "wherein", "whereupon", "wherever", "whether", "which", "while", "whither", "who", "whoever", "whole", "whom", "whose", 
	        	"why", "wib", "will", "with", "within", "without", "would", "ya", "yaitu", "yakni", "yang", "yet", "you", "your", "yours", "yourself", "yourselves"
	        );

		public function casefolding($text){
			$replace = preg_replace('@[�?:;,."/\'+-=!~#()0-9]+@', " ", strtolower($text));
			$data[] = $replace;
			return $data;
		}

		public function tokenizing($text){
			$data = str_word_count($text, 1);
			foreach ($data as $key => $value) {
				$data[$key] = $value;
			}
			return $data;
		}

		public function filtering($text){
			$data = $this->tokenizing($text);
			$data = array_diff($data, $this->stopwords2);
			$word_count = array_count_values($data);
			return array_keys($word_count);
		}

		public function stemming($text){
			// $stem = Stem::stemming($text);
			// return $stem;
		}

		public function tf_idf($term, $id) {
            if ($term != '') {
                $resCount = mysql_query("SELECT * FROM tbindex WHERE term = '$term'");
                $num_rows = mysql_num_rows($resCount);
                if ($num_rows > 0) {
                    $rowcount = mysql_fetch_array($resCount);
                    $count = $rowcount['jumlah'];
                    $count++;
                    mysql_query("UPDATE tbindex SET jumlah = $count WHERE term = '$term' AND id_doc = $id");
                }
                else {
                    mysql_query("INSERT INTO tbindex (term, id_doc, jumlah) VALUES ('$term', $id, 1)");
                }
            }
        }

		public function hitungBobot(){
			// hitung jumlah dokumen
			$sqlJumDoc = mysql_query("SELECT DISTINCT id_doc from tbindex");
			$jumDoc = mysql_num_rows($sqlJumDoc);

			// ambil setiap record dalam tabel tbindex
			// hitung bobot untuk setiap Term dalam setiap DocId
			$sqlJumTerm = mysql_query("SELECT * FROM tbindex ORDER BY id_index");
			$jumTerm = mysql_num_rows($sqlJumTerm);
			while ($rowUji = mysql_fetch_array($sqlJumTerm)) {
				$term = $rowUji['term'];
				$tf = $rowUji['jumlah'];
				$id = $rowUji['id_index'];

				// jumlah dokumen dengan term tertentu
				$sqlTermInDoc = mysql_query("SELECT COUNT(*) as jumDoc from tbindex where term = '".$term."'");
				$rowTerm = mysql_fetch_array($sqlTermInDoc);
				$nTerm = $rowTerm['jumDoc'];

				$w = $tf * (log10($jumDoc / $nTerm));

				// update bobot term di tabel tbindex
				$updateTbIndex = mysql_query("UPDATE tbindex SET bobot = '".$w."' WHERE id_index = '".$id."'");
			}
		}
	}
?>