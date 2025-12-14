<div class="footer">
    <div class="footer-left">
        <img src="assets/foto1.png" alt="InsightX">
        <p>Koneksi cepat, stabil, dan terpercaya untuk semua aktivitas Anda.</p>
    </div>

    <div class="footer-right">
        <h4>Stay Connected With Us</h4>
        <div class="footer-icons">
            <i class="ri-facebook-fill"></i>
            <i class="ri-instagram-line"></i>
            <i class="ri-twitter-x-line"></i>
            <i class="ri-youtube-fill"></i>
        </div>
    </div>
</div>


<style>
.footer {
    width: calc(100% - 230px);   /* 260px = lebar sidebar */
    margin-left: 220px;          /* geser sejajar main content */
    background: #0c0d13;
    padding: 30px 40px;
    border-radius:0;
    color: #fff;
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-top: 2px solid #1e90ff;
    box-sizing: border-box;
}

/* Footer column kiri */
.footer-left img {
    width: 130px;
    margin-bottom: 10px;
}

.footer-left p {
    margin: 0;
    color: #ddd;
    font-size: 14px;
}

/* Footer column kanan */
.footer-right {
    text-align: center;
}

.footer-right h4 {
    margin-bottom: 10px;
    font-weight: 500;
}

.footer-icons {
    display: flex;
    gap: 18px;
    justify-content: center;
    font-size: 24px;
}

.footer-icons i {
    cursor: pointer;
}

.social-icons {
  display: flex;
  gap: 15px;
}

.social-icons i {
  color: #fff;               /* warna default */
  transition: 0.3s ease;     /* transisi aktif */
}


.social-icons i:hover {
  transform: translateY(-4px) scale(1.15);
  background: #1e90ff;
  box-shadow: 0 0 10px #1e90ff;
}

/* Fade-in animation */
@keyframes fadeIn {
  from { opacity: 0; transform: translateY(20px); }
  to { opacity: 1; transform: translateY(0); }
}

/* Responsive */
@media (max-width: 768px) {
  footer.footer {
    flex-direction: column;
    text-align: center;
    gap: 25px;
  }

  .footer-right {
    text-align: center;
  }
}
</style>
