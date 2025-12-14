INSERT INTO donasi (user_id,payment_id,nominal) VALUES (1,0,100000);
SET @d = LAST_INSERT_ID();
INSERT INTO pembayaran (donation_id,user_id,metode) VALUES (@d,1,'transfer');
SET @p = LAST_INSERT_ID();
UPDATE donasi SET payment_id=@p WHERE donation_id=@d;

SELECT 'LAST_DONASI' as marker, donasi.* FROM donasi ORDER BY donation_id DESC LIMIT 1;
SELECT 'LAST_PEMBAYARAN' as marker, pembayaran.* FROM pembayaran ORDER BY payment_id DESC LIMIT 1;
SELECT u.user_id,u.nama,COALESCE(SUM(d.nominal),0) AS total, COUNT(d.donation_id) AS transaksi
FROM users u
LEFT JOIN donasi d ON u.user_id=d.user_id
GROUP BY u.user_id
ORDER BY total DESC, transaksi DESC
LIMIT 10;
